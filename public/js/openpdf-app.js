(() => {
  const { createApp } = window.Vue;
  const config = window.OPENPDF_CONFIG || {};

  createApp({
    data() {
      return {
        config,
        tools: config.tools || [],
        selectedToolKey: config.selectedToolKey || (config.tools && config.tools[0] && config.tools[0].key) || "jpg_to_pdf",
        selectedLocale: config.locale || "en",
        files: [],
        dropHover: false,
        busy: false,
        sortable: null,
        auth: config.auth || { logged_in: false, provider: "visitor" },
        statusMessage: (config.i18n && config.i18n.workspace && config.i18n.workspace.ready) || "Ready.",
        options: {
          orientation: "portrait",
          pageSize: "a4",
          margin: 0,
          singleFile: true
        }
      };
    },
    computed: {
      selectedTool() {
        return this.tools.find((tool) => tool.key === this.selectedToolKey) || null;
      },
      acceptAttribute() {
        if (!this.selectedTool) return "";
        return this.selectedTool.accept_extensions.map((ext) => `.${ext}`).join(",");
      },
      visitorLimitLabel() {
        const mb = Math.floor((this.config.limits?.max_bytes || 0) / (1024 * 1024));
        return `${this.config.i18n?.limits?.visitor_title || "Visitor"}: ${this.config.limits?.max_files || 100} / ${mb} MB`;
      },
      visitorFilesLabel() {
        const maxFiles = String(this.config.limits?.max_files || 100);
        return (this.config.i18n?.limits?.visitor_limit_files || "Maximum 100 files per task").replace("100", maxFiles);
      },
      visitorSizeLabel() {
        const limitMb = Math.floor((this.config.limits?.max_bytes || 0) / (1024 * 1024));
        return (this.config.i18n?.limits?.visitor_limit_size || "Maximum 100 MB per task").replace("100", String(limitMb));
      }
    },
    mounted() {
      this.bootstrapAxios();
      this.refreshSession();
      this.initGoogle();
      this.ensureSortable();
    },
    beforeUnmount() {
      this.destroySortable();
    },
    methods: {
      bootstrapAxios() {
        const csrf = document.querySelector('meta[name="csrf-token"]');

        window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

        if (csrf) {
          window.axios.defaults.headers.common["X-CSRF-TOKEN"] = csrf.getAttribute("content");
        }
      },
      iconForTool(toolKey) {
        const icons = {
          pdf_to_word: "bi bi-file-earmark-word",
          pdf_to_excel: "bi bi-file-earmark-excel",
          pdf_to_jpg: "bi bi-filetype-jpg",
          compress_pdf: "bi bi-file-zip",
          merge_pdf: "bi bi-files",
          word_to_pdf: "bi bi-file-earmark-pdf",
          excel_to_pdf: "bi bi-file-earmark-pdf",
          jpg_to_pdf: "bi bi-images"
        };

        return icons[toolKey] || "bi bi-file-earmark";
      },
      toolUrl(toolKey, locale) {
        const localeCode = locale || this.selectedLocale;
        const fromMap = this.config.toolUrlsByLocale?.[localeCode]?.[toolKey];
        if (fromMap) {
          return fromMap;
        }

        const tool = this.tools.find((item) => item.key === toolKey);
        return tool?.url || null;
      },
      selectTool(toolKey) {
        if (!toolKey) return;
        const target = this.toolUrl(toolKey, this.selectedLocale);
        if (target) {
          window.location.href = target;
          return;
        }

        this.selectedToolKey = toolKey;
        this.files = [];
        this.statusMessage = this.config.i18n?.workspace?.ready || "Ready.";
      },
      switchLocale() {
        const target = this.toolUrl(this.selectedToolKey, this.selectedLocale);
        if (target) {
          window.location.href = target;
          return;
        }

        const fallback = this.config.siteMapUrlsByLocale?.[this.selectedLocale] || this.config.homeUrl || "/";
        window.location.href = fallback;
      },
      onInputChange(event) {
        this.handleFiles(event.target.files);
        event.target.value = "";
      },
      onDrop(event) {
        this.dropHover = false;
        this.handleFiles(event.dataTransfer.files);
      },
      async handleFiles(fileList) {
        const incoming = Array.from(fileList || []);
        if (!incoming.length || !this.selectedTool) return;

        const validFiles = incoming.filter((file) => this.isAccepted(file));

        if (!validFiles.length) {
          this.statusMessage = this.config.i18n?.status?.invalid_files || "No valid files found for selected tool.";
          return;
        }

        const mergedCount = this.files.length + validFiles.length;
        const mergedBytes = this.files.reduce((sum, file) => sum + file.size, 0) + validFiles.reduce((sum, file) => sum + file.size, 0);

        if (!this.auth.logged_in) {
          if (mergedCount > (this.config.limits?.max_files || 100)) {
            this.statusMessage = this.config.i18n?.status?.limit_files || `Visitor limit exceeded: max ${this.config.limits?.max_files || 100} files.`;
            return;
          }

          if (mergedBytes > (this.config.limits?.max_bytes || 100 * 1024 * 1024)) {
            this.statusMessage = this.config.i18n?.status?.limit_size || "Visitor size limit exceeded.";
            return;
          }
        }

        const prepared = await Promise.all(
          validFiles.map(async (file) => {
            const preview = this.selectedTool.key === "jpg_to_pdf" ? await readFileAsDataUrl(file) : null;

            return {
              id: crypto.randomUUID ? crypto.randomUUID() : String(Date.now() + Math.random()),
              file,
              name: file.name,
              size: file.size,
              type: file.type,
              preview,
              rotation: 0
            };
          })
        );

        this.files.push(...prepared);
        this.statusMessage = this.config.i18n?.status?.files_added || `${prepared.length} file(s) added.`;
        this.ensureSortable();
      },
      isAccepted(file) {
        if (!this.selectedTool) return false;

        const extension = (file.name.split(".").pop() || "").toLowerCase();
        const mime = (file.type || "").toLowerCase();
        const extOk = this.selectedTool.accept_extensions.includes(extension);
        const mimeOk = mime && this.selectedTool.accept_mime.includes(mime);

        return extOk || mimeOk;
      },
      remove(index) {
        this.files.splice(index, 1);

        if (this.files.length === 0) {
          this.statusMessage = this.config.i18n?.workspace?.ready || "Ready.";
        }
      },
      rotate(index) {
        if (!this.files[index]) return;
        this.files[index].rotation = (this.files[index].rotation + 90) % 360;
      },
      ensureSortable() {
        if (this.selectedTool?.key !== "jpg_to_pdf") {
          this.destroySortable();
          return;
        }

        this.$nextTick(() => {
          if (this.sortable || !window.Sortable || !this.$refs.sortableGrid) return;

          this.sortable = window.Sortable.create(this.$refs.sortableGrid, {
            animation: 150,
            ghostClass: "sortable-ghost",
            onEnd: (event) => {
              if (event.oldIndex == null || event.newIndex == null || event.oldIndex === event.newIndex) {
                return;
              }

              const [moved] = this.files.splice(event.oldIndex, 1);
              this.files.splice(event.newIndex, 0, moved);
            }
          });
        });
      },
      destroySortable() {
        if (this.sortable) {
          this.sortable.destroy();
          this.sortable = null;
        }
      },
      async convert() {
        if (!this.selectedTool || !this.files.length || this.busy) return;

        this.busy = true;

        try {
          if (this.selectedTool.key === "jpg_to_pdf") {
            await this.convertJpgToPdfInBrowser();
          } else {
            await this.convertViaBackend();
          }
        } catch (error) {
          console.error(error);
          this.statusMessage = error?.response?.data?.message || error.message || this.config.i18n?.status?.failed || "Conversion failed.";
        } finally {
          this.busy = false;
        }
      },
      async convertJpgToPdfInBrowser() {
        if (!(window.jspdf && window.jspdf.jsPDF)) {
          throw new Error("jsPDF is not loaded.");
        }

        this.statusMessage = this.config.i18n?.status?.processing || "Processing...";

        if (this.options.singleFile) {
          const pdf = await this.buildPdf(this.files);
          const outputName = `openpdf-${Date.now()}.pdf`;
          pdf.save(outputName);
          await this.logBrowserTask(outputName);
        } else {
          for (const file of this.files) {
            const pdf = await this.buildPdf([file]);
            const outputName = `${stripExtension(file.name)}.pdf`;
            pdf.save(outputName);
          }

          await this.logBrowserTask(`openpdf-batch-${Date.now()}.zip`);
        }

        this.statusMessage = this.config.i18n?.status?.browser_done || "Browser conversion completed.";
      },
      async buildPdf(items) {
        const orientation = this.options.orientation === "landscape" ? "l" : "p";
        const pageSize = this.options.pageSize;
        const margin = Number(this.options.margin || 0);
        let pdf;

        for (const item of items) {
          const source = await getRotatedSource(item.preview, item.rotation || 0);
          const format = pageSize === "fit" ? [source.width, source.height] : pageSize;

          if (!pdf) {
            pdf = new window.jspdf.jsPDF({ orientation, unit: "mm", format });
          } else {
            pdf.addPage(format, orientation);
          }

          const pageWidth = pdf.internal.pageSize.getWidth();
          const pageHeight = pdf.internal.pageSize.getHeight();
          const draw = fitBox(source.width, source.height, pageWidth - margin * 2, pageHeight - margin * 2);

          pdf.addImage(
            source.dataUrl,
            "JPEG",
            (pageWidth - draw.w) / 2,
            (pageHeight - draw.h) / 2,
            draw.w,
            draw.h,
            undefined,
            "FAST"
          );
        }

        return pdf;
      },
      async logBrowserTask(outputName) {
        await window.axios.post("/api/conversions", {
          tool_key: this.selectedTool.key,
          source: "browser",
          options: this.options,
          output: {
            name: outputName,
            mime: "application/pdf",
            size: 0
          },
          files: this.files.map((file) => ({
            name: file.name,
            size: file.size,
            type: file.type,
            rotation: file.rotation || 0
          }))
        });
      },
      async convertViaBackend() {
        this.statusMessage = this.config.i18n?.status?.queued || "Queued...";

        const serializedFiles = await Promise.all(
          this.files.map(async (file) => ({
            name: file.name,
            size: file.size,
            type: file.type,
            rotation: file.rotation || 0,
            data: file.preview || (await readFileAsDataUrl(file.file))
          }))
        );

        const createResponse = await window.axios.post("/api/conversions", {
          tool_key: this.selectedTool.key,
          source: "backend",
          options: this.options,
          files: serializedFiles
        });

        const task = createResponse.data.task;
        const finalTask = await this.pollTask(task.id);

        if (finalTask.status !== "completed" || !finalTask.download_url) {
          throw new Error(finalTask.error_message || this.config.i18n?.status?.failed || "Conversion failed.");
        }

        this.statusMessage = this.config.i18n?.status?.downloading || "Downloading output...";
        window.location.href = finalTask.download_url;
        this.statusMessage = this.config.i18n?.status?.completed || "Completed.";
      },
      async pollTask(taskId) {
        for (let i = 0; i < 240; i += 1) {
          const response = await window.axios.get(`/api/conversions/${taskId}`);
          const task = response.data.task;

          if (task.status === "completed" || task.status === "failed") {
            return task;
          }

          this.statusMessage = this.config.i18n?.status?.processing || "Processing...";
          await new Promise((resolve) => setTimeout(resolve, 1500));
        }

        throw new Error(this.config.i18n?.status?.timeout || "Polling timeout.");
      },
      async refreshSession() {
        try {
          const response = await window.axios.get("/api/auth/session");
          this.auth = response.data.auth;
        } catch (error) {
          console.error(error);
        }
      },
      async logout() {
        await window.axios.post("/api/auth/logout", {});
        this.auth = { logged_in: false, provider: "visitor", name: null, email: null };
      },
      initGoogle() {
        if (!this.config.googleClientId) return;

        const mountGoogle = () => {
          if (!(window.google && window.google.accounts && window.google.accounts.id)) {
            setTimeout(mountGoogle, 200);
            return;
          }

          window.google.accounts.id.initialize({
            client_id: this.config.googleClientId,
            callback: this.handleGoogleCredential
          });

          window.google.accounts.id.renderButton(document.getElementById("googleButton"), {
            theme: "outline",
            size: "large",
            text: "signin_with",
            shape: "pill"
          });
        };

        mountGoogle();
      },
      async handleGoogleCredential(response) {
        if (!response || !response.credential) return;

        const result = await window.axios.post("/api/auth/google", {
          credential: response.credential
        });

        this.auth = result.data.auth;
        this.statusMessage = this.config.i18n?.status?.google_success || "Google sign-in successful.";
      },
      formatBytes(bytes) {
        if (bytes < 1024) return `${bytes} B`;
        if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
        return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
      }
    }
  }).mount("#openpdf-app");

  async function readFileAsDataUrl(file) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.onload = () => resolve(reader.result);
      reader.onerror = reject;
      reader.readAsDataURL(file);
    });
  }

  function stripExtension(name) {
    return name.replace(/\.[^/.]+$/, "");
  }

  function fitBox(srcWidth, srcHeight, maxWidth, maxHeight) {
    const ratio = Math.min(maxWidth / srcWidth, maxHeight / srcHeight);

    return {
      w: srcWidth * ratio,
      h: srcHeight * ratio
    };
  }

  function loadImage(src) {
    return new Promise((resolve, reject) => {
      const image = new Image();
      image.onload = () => resolve(image);
      image.onerror = reject;
      image.src = src;
    });
  }

  async function getRotatedSource(src, rotation) {
    const angle = ((rotation % 360) + 360) % 360;
    const image = await loadImage(src);

    if (angle === 0) {
      return { dataUrl: src, width: image.width, height: image.height };
    }

    const swap = angle === 90 || angle === 270;
    const canvas = document.createElement("canvas");
    canvas.width = swap ? image.height : image.width;
    canvas.height = swap ? image.width : image.height;

    const context = canvas.getContext("2d");
    context.translate(canvas.width / 2, canvas.height / 2);
    context.rotate((angle * Math.PI) / 180);
    context.drawImage(image, -image.width / 2, -image.height / 2);

    return {
      dataUrl: canvas.toDataURL("image/jpeg", 0.95),
      width: canvas.width,
      height: canvas.height
    };
  }
})();
