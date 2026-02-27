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
        feedbackMessage: "",
        feedbackType: "info",
        resultReady: false,
        resultUrl: null,
        resultFileName: "",
        resultTitle: "",
        resultButtonLabel: "",
        splitPdfBytes: null,
        splitPages: [],
        splitViewMode: "pages",
        splitExtractMode: "selected",
        splitSelectionInput: "",
        splitMergeExtracted: false,
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
      isSplitTool() {
        return this.selectedTool?.key === "split_pdf";
      },
      acceptAttribute() {
        if (!this.selectedTool) return "";
        return this.selectedTool.accept_extensions.map((ext) => `.${ext}`).join(",");
      },
      primaryExtension() {
        return (this.selectedTool?.accept_extensions?.[0] || "file").toUpperCase();
      },
      selectedSplitPages() {
        return this.splitPages.filter((page) => page.selected).map((page) => page.number);
      },
      canConvert() {
        if (this.isSplitTool) {
          return this.files.length > 0 && this.selectedSplitPages.length > 0;
        }

        return this.files.length > 0;
      },
      selectButtonLabel() {
        if (!this.selectedTool) {
          return this.config.i18n?.workspace?.select_files || "Select files";
        }

        const template = this.config.i18n?.workspace?.select_files_format;
        if (template) {
          return template.replace(":type", this.primaryExtension);
        }

        return `${this.config.i18n?.workspace?.select_files || "Select"} ${this.primaryExtension} ${this.config.i18n?.workspace?.files_suffix || "files"}`;
      },
      dropSubtitleLabel() {
        if (this.isSplitTool) {
          return this.config.i18n?.workspace?.split_drop_subtitle || this.config.i18n?.workspace?.drop_subtitle_action || "or drop PDF here";
        }

        return this.config.i18n?.workspace?.drop_subtitle_action || this.config.i18n?.workspace?.drop_subtitle || "or drop files here";
      },
      sidebarHintText() {
        if (this.isSplitTool) {
          if (!this.splitPages.length) {
            return this.config.i18n?.workspace?.split_hint_upload || "Upload one PDF to start selecting pages.";
          }

          if (this.splitExtractMode === "all") {
            const outputs = this.splitMergeExtracted ? 1 : this.splitPages.length;
            return interpolate(
              this.config.i18n?.workspace?.split_hint_all || "All :count pages will be extracted. :output output file(s) will be created.",
              { count: this.splitPages.length, output: outputs }
            );
          }

          if (!this.selectedSplitPages.length) {
            return this.config.i18n?.workspace?.split_no_selection || "Select at least one page.";
          }

          if (this.splitMergeExtracted) {
            return interpolate(
              this.config.i18n?.workspace?.split_hint_selected_merge || "Selected pages (:count) will be merged into one PDF file.",
              { count: this.selectedSplitPages.length }
            );
          }

          return interpolate(
            this.config.i18n?.workspace?.split_hint_selected_separate || "Selected pages (:count) will be converted into separate PDF files. :output file(s) will be created.",
            { count: this.selectedSplitPages.length, output: this.selectedSplitPages.length }
          );
        }

        if (this.selectedTool?.key === "merge_pdf" && this.files.length <= 1) {
          const template = this.config.i18n?.workspace?.hint_add_more || "Please select more files by clicking again on the select button.";
          return template.replace(":type", this.primaryExtension);
        }

        if (this.files.length === 1) {
          return this.config.i18n?.workspace?.hint_ready_to_convert || "File is ready. Click Convert to continue.";
        }

        return this.config.i18n?.workspace?.hint_sort || "To change the order, drag and drop the files as you want.";
      },
      panelConvertLabel() {
        if (this.isSplitTool) {
          return this.config.i18n?.workspace?.split_action || this.selectedTool?.title || "Split PDF";
        }

        return this.selectedTool?.title || this.config.i18n?.workspace?.convert || "Convert";
      }
    },
    mounted() {
      this.bootstrapAxios();
      this.refreshSession();
      this.initGoogle();
      this.ensureSortable();
      this.configurePdfJsWorker();
    },
    beforeUnmount() {
      this.destroySortable();
      this.cleanupResultUrl();
    },
    methods: {
      bootstrapAxios() {
        const csrf = document.querySelector('meta[name="csrf-token"]');

        window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

        if (csrf) {
          window.axios.defaults.headers.common["X-CSRF-TOKEN"] = csrf.getAttribute("content");
        }
      },
      configurePdfJsWorker() {
        if (!(window.pdfjsLib && window.pdfjsLib.GlobalWorkerOptions)) return;
        window.pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";
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
        this.resetSplitState();
        this.resultReady = false;
        this.clearFeedback();
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
      triggerFileSelect() {
        if (this.busy) return;
        this.$refs.fileInput?.click();
      },
      onInputChange(event) {
        this.handleFiles(event.target.files);
        event.target.value = "";
      },
      onDrop(event) {
        this.dropHover = false;
        this.handleFiles(event.dataTransfer.files);
      },
      setFeedback(type, message) {
        this.feedbackType = type || "info";
        this.feedbackMessage = message || "";
      },
      clearFeedback() {
        this.feedbackType = "info";
        this.feedbackMessage = "";
      },
      async handleFiles(fileList) {
        const incoming = Array.from(fileList || []);

        if (!incoming.length || !this.selectedTool) return;

        const validFiles = incoming.filter((file) => this.isAccepted(file));
        if (!validFiles.length) {
          this.setFeedback("error", this.config.i18n?.status?.invalid_files || "No valid files found for selected tool.");
          return;
        }

        const filesForTool = this.isSplitTool ? [validFiles[0]] : validFiles;
        const mergedCount = this.isSplitTool
          ? filesForTool.length
          : this.files.length + filesForTool.length;
        const mergedBytes = this.isSplitTool
          ? filesForTool.reduce((sum, file) => sum + file.size, 0)
          : this.files.reduce((sum, file) => sum + file.size, 0) + filesForTool.reduce((sum, file) => sum + file.size, 0);

        if (!this.auth.logged_in) {
          if (mergedCount > (this.config.limits?.max_files || 100)) {
            this.setFeedback("error", this.config.i18n?.status?.limit_files || `Visitor limit exceeded: max ${this.config.limits?.max_files || 100} files.`);
            return;
          }

          if (mergedBytes > (this.config.limits?.max_bytes || 100 * 1024 * 1024)) {
            this.setFeedback("error", this.config.i18n?.status?.limit_size || "Visitor size limit exceeded.");
            return;
          }
        }

        const prepared = await Promise.all(
          filesForTool.map(async (file) => {
            const extension = (file.name.split(".").pop() || "").toLowerCase();
            const preview = this.isSplitTool ? null : await this.createPreview(file, extension);

            return {
              id: crypto.randomUUID ? crypto.randomUUID() : String(Date.now() + Math.random()),
              file,
              name: file.name,
              size: file.size,
              type: file.type,
              extension: extension.toUpperCase(),
              preview,
              rotation: 0
            };
          })
        );

        this.resultReady = false;
        this.cleanupResultUrl();

        if (this.isSplitTool) {
          this.files = [prepared[0]];
          this.destroySortable();
          await this.prepareSplitDocument(prepared[0].file);

          if (validFiles.length > 1) {
            this.setFeedback("info", this.config.i18n?.status?.split_single_file_only || "Split PDF works with one file only. First file was used.");
          } else {
            this.clearFeedback();
          }

          return;
        }

        this.files.push(...prepared);
        this.clearFeedback();
        this.ensureSortable();
      },
      async createPreview(file, extension) {
        const mime = (file.type || "").toLowerCase();

        if (mime.startsWith("image/")) {
          return readFileAsDataUrl(file);
        }

        if (extension === "pdf") {
          return renderPdfPreview(file);
        }

        return null;
      },
      async prepareSplitDocument(file) {
        if (!window.pdfjsLib) {
          throw new Error("PDF.js is not loaded.");
        }

        const arrayBuffer = await file.arrayBuffer();
        this.splitPdfBytes = arrayBuffer.slice(0);

        const loadingTask = window.pdfjsLib.getDocument({ data: arrayBuffer });
        const pdfDoc = await loadingTask.promise;
        const pages = [];

        for (let index = 1; index <= pdfDoc.numPages; index += 1) {
          const preview = await renderPdfPagePreview(pdfDoc, index, 260);
          pages.push({
            number: index,
            preview,
            selected: true
          });
        }

        this.splitPages = pages;
        this.splitViewMode = "pages";
        this.splitExtractMode = "selected";
        this.splitMergeExtracted = false;
        this.splitSelectionInput = formatPageSelection(this.selectedSplitPages);
      },
      resetSplitState() {
        this.splitPdfBytes = null;
        this.splitPages = [];
        this.splitViewMode = "pages";
        this.splitExtractMode = "selected";
        this.splitSelectionInput = "";
        this.splitMergeExtracted = false;
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
          this.destroySortable();
          this.resetSplitState();
        }
      },
      rotate(index) {
        if (!this.files[index]) return;
        this.files[index].rotation = (this.files[index].rotation + 90) % 360;
      },
      sortFilesByName() {
        if (this.files.length < 2 || this.isSplitTool) return;

        this.files.sort((a, b) => a.name.localeCompare(b.name, undefined, { numeric: true, sensitivity: "base" }));
      },
      ensureSortable() {
        this.$nextTick(() => {
          if (this.isSplitTool || !window.Sortable || !this.$refs.sortableGrid) return;

          if (this.sortable && this.sortable.el !== this.$refs.sortableGrid) {
            this.destroySortable();
          }

          if (this.sortable) return;

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
      setSplitViewMode(mode) {
        this.splitViewMode = mode;
      },
      setSplitExtractMode(mode) {
        this.splitExtractMode = mode;

        if (mode === "all") {
          this.splitPages = this.splitPages.map((page) => ({ ...page, selected: true }));
          this.splitSelectionInput = formatPageSelection(this.selectedSplitPages);
          return;
        }

        if (!this.selectedSplitPages.length && this.splitPages.length) {
          this.splitPages = this.splitPages.map((page, index) => ({ ...page, selected: index === 0 }));
        }

        this.splitSelectionInput = formatPageSelection(this.selectedSplitPages);
      },
      toggleSplitPage(pageNumber) {
        if (this.splitExtractMode === "all") {
          return;
        }

        this.splitPages = this.splitPages.map((page) => (
          page.number === pageNumber ? { ...page, selected: !page.selected } : page
        ));

        this.splitSelectionInput = formatPageSelection(this.selectedSplitPages);
      },
      applySplitSelectionInput() {
        if (!this.splitPages.length) return;

        const parsed = parsePageSelection(this.splitSelectionInput, this.splitPages.length);
        if (!parsed) {
          this.setFeedback("error", this.config.i18n?.status?.split_invalid_range || "Invalid page range format.");
          return;
        }

        const selectedSet = new Set(parsed);
        this.splitExtractMode = "selected";
        this.splitPages = this.splitPages.map((page) => ({
          ...page,
          selected: selectedSet.has(page.number)
        }));
        this.splitSelectionInput = formatPageSelection(parsed);

        if (!parsed.length) {
          this.setFeedback("error", this.config.i18n?.status?.split_no_pages_selected || "Select at least one page.");
        } else {
          this.clearFeedback();
        }
      },
      async convert() {
        if (!this.selectedTool || !this.canConvert || this.busy) return;

        this.busy = true;
        this.clearFeedback();

        try {
          if (this.selectedTool.key === "split_pdf") {
            await this.splitPdfInBrowser();
          } else if (this.selectedTool.key === "jpg_to_pdf") {
            await this.convertJpgToPdfInBrowser();
          } else {
            await this.convertViaBackend();
          }
        } catch (error) {
          console.error(error);
          this.setFeedback("error", error?.response?.data?.message || error.message || this.config.i18n?.status?.failed || "Conversion failed.");
        } finally {
          this.busy = false;
        }
      },
      async splitPdfInBrowser() {
        if (!(window.PDFLib && window.PDFLib.PDFDocument)) {
          throw new Error("pdf-lib is not loaded.");
        }

        if (!this.splitPdfBytes) {
          throw new Error(this.config.i18n?.workspace?.split_hint_upload || "Upload a PDF first.");
        }

        const selectedPages = this.selectedSplitPages;
        if (!selectedPages.length) {
          throw new Error(this.config.i18n?.status?.split_no_pages_selected || "Select at least one page.");
        }

        const sourcePdf = await window.PDFLib.PDFDocument.load(this.splitPdfBytes.slice(0));

        if (this.splitMergeExtracted || selectedPages.length === 1) {
          const outputPdf = await window.PDFLib.PDFDocument.create();
          const copiedPages = await outputPdf.copyPages(sourcePdf, selectedPages.map((num) => num - 1));
          copiedPages.forEach((page) => outputPdf.addPage(page));

          const outputBytes = await outputPdf.save();
          const outputBlob = new Blob([outputBytes], { type: "application/pdf" });
          const outputName = this.splitMergeExtracted
            ? `split-pages-${Date.now()}.pdf`
            : `page-${selectedPages[0]}.pdf`;

          await this.logBrowserTask(
            outputName,
            outputBlob.size || 0,
            {
              split: {
                mode: this.splitExtractMode,
                merge_into_one: this.splitMergeExtracted,
                pages: selectedPages
              }
            },
            "application/pdf"
          );

          this.showResult({
            url: URL.createObjectURL(outputBlob),
            name: outputName,
            title: this.config.i18n?.workspace?.split_result_ready || "PDF pages extracted!",
            button: this.config.i18n?.workspace?.split_download_pdf || "Download split PDF"
          });

          return;
        }

        if (!window.JSZip) {
          throw new Error("JSZip is not loaded.");
        }

        const zip = new window.JSZip();

        for (const pageNumber of selectedPages) {
          const pagePdf = await window.PDFLib.PDFDocument.create();
          const [copied] = await pagePdf.copyPages(sourcePdf, [pageNumber - 1]);
          pagePdf.addPage(copied);
          const bytes = await pagePdf.save();
          zip.file(`page-${pageNumber}.pdf`, bytes);
        }

        const zipBlob = await zip.generateAsync({ type: "blob" });
        const zipName = `split-pages-${Date.now()}.zip`;

        await this.logBrowserTask(
          zipName,
          zipBlob.size || 0,
          {
            split: {
              mode: this.splitExtractMode,
              merge_into_one: this.splitMergeExtracted,
              pages: selectedPages
            }
          },
          "application/zip"
        );

        this.showResult({
          url: URL.createObjectURL(zipBlob),
          name: zipName,
          title: this.config.i18n?.workspace?.split_result_zip_ready || "PDF pages extracted!",
          button: this.config.i18n?.workspace?.split_download_zip || "Download ZIP"
        });
      },
      async convertJpgToPdfInBrowser() {
        if (!(window.jspdf && window.jspdf.jsPDF)) {
          throw new Error("jsPDF is not loaded.");
        }

        if (!this.options.singleFile) {
          for (const file of this.files) {
            const pdf = await this.buildPdf([file]);
            const blob = pdf.output("blob");
            const outputName = `${stripExtension(file.name)}.pdf`;
            const url = URL.createObjectURL(blob);

            triggerDownload(url, outputName);
            URL.revokeObjectURL(url);
          }

          await this.logBrowserTask(`openpdf-batch-${Date.now()}.zip`, 0);
          this.setFeedback("info", this.config.i18n?.status?.browser_done || "Browser conversion completed.");
          return;
        }

        const pdf = await this.buildPdf(this.files);
        const outputName = `openpdf-${Date.now()}.pdf`;
        const blob = pdf.output("blob");

        await this.logBrowserTask(outputName, blob.size || 0);

        this.showResult({
          url: URL.createObjectURL(blob),
          name: outputName,
          title: this.config.i18n?.workspace?.result_ready || "Your file is ready!",
          button: this.config.i18n?.workspace?.download_result || "Download file"
        });
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
      async logBrowserTask(outputName, outputSize, optionOverrides = null, outputMime = "application/pdf") {
        const options = optionOverrides ? { ...this.options, ...optionOverrides } : this.options;

        await window.axios.post("/api/conversions", {
          tool_key: this.selectedTool.key,
          source: "browser",
          options,
          output: {
            name: outputName,
            mime: outputMime,
            size: outputSize || 0
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
        const serializedFiles = await Promise.all(
          this.files.map(async (file) => ({
            name: file.name,
            size: file.size,
            type: file.type,
            rotation: file.rotation || 0,
            data: await readFileAsDataUrl(file.file)
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

        this.showResult(this.buildResultPayload(finalTask));
      },
      buildResultPayload(finalTask) {
        if (this.selectedTool?.key === "merge_pdf") {
          return {
            url: finalTask.download_url,
            name: finalTask.output_name || "merged.pdf",
            title: this.config.i18n?.workspace?.result_merged || "PDFs have been merged!",
            button: this.config.i18n?.workspace?.download_merged || "Download merged PDF"
          };
        }

        return {
          url: finalTask.download_url,
          name: finalTask.output_name || `openpdf-${Date.now()}.bin`,
          title: this.config.i18n?.workspace?.result_ready || "Your file is ready!",
          button: this.config.i18n?.workspace?.download_result || "Download file"
        };
      },
      showResult(payload) {
        this.cleanupResultUrl();
        this.destroySortable();
        this.resultReady = true;
        this.resultUrl = payload.url;
        this.resultFileName = payload.name;
        this.resultTitle = payload.title;
        this.resultButtonLabel = payload.button;
      },
      resetWorkspace() {
        this.cleanupResultUrl();
        this.resultReady = false;
        this.resultUrl = null;
        this.resultFileName = "";
        this.resultTitle = "";
        this.resultButtonLabel = "";
        this.ensureSortable();
      },
      cleanupResultUrl() {
        if (this.resultUrl && String(this.resultUrl).startsWith("blob:")) {
          URL.revokeObjectURL(this.resultUrl);
        }
      },
      async pollTask(taskId) {
        for (let i = 0; i < 240; i += 1) {
          const response = await window.axios.get(`/api/conversions/${taskId}`);
          const task = response.data.task;

          if (task.status === "completed" || task.status === "failed") {
            return task;
          }

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

          const googleButton = document.getElementById("googleButton");
          if (!googleButton) return;

          window.google.accounts.id.initialize({
            client_id: this.config.googleClientId,
            callback: this.handleGoogleCredential
          });

          window.google.accounts.id.renderButton(googleButton, {
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

  async function renderPdfPreview(file) {
    if (!window.pdfjsLib) {
      return null;
    }

    try {
      const arrayBuffer = await file.arrayBuffer();
      const loadingTask = window.pdfjsLib.getDocument({ data: arrayBuffer });
      const pdfDoc = await loadingTask.promise;
      return renderPdfPagePreview(pdfDoc, 1, 260);
    } catch (error) {
      console.warn("PDF preview could not be generated:", error);
      return null;
    }
  }

  async function renderPdfPagePreview(pdfDoc, pageNumber, targetWidth) {
    const page = await pdfDoc.getPage(pageNumber);
    const viewport = page.getViewport({ scale: 1 });
    const scale = targetWidth / viewport.width;
    const scaledViewport = page.getViewport({ scale });
    const canvas = document.createElement("canvas");
    const context = canvas.getContext("2d");

    canvas.width = Math.floor(scaledViewport.width);
    canvas.height = Math.floor(scaledViewport.height);

    await page.render({
      canvasContext: context,
      viewport: scaledViewport
    }).promise;

    return canvas.toDataURL("image/jpeg", 0.9);
  }

  function parsePageSelection(input, maxPage) {
    const trimmed = String(input || "").trim();
    if (trimmed === "") {
      return [];
    }

    const tokens = trimmed.split(",").map((item) => item.trim()).filter(Boolean);
    const numbers = new Set();

    for (const token of tokens) {
      const rangeMatch = token.match(/^(\d+)\s*-\s*(\d+)$/);

      if (rangeMatch) {
        let start = Number(rangeMatch[1]);
        let end = Number(rangeMatch[2]);

        if (!Number.isInteger(start) || !Number.isInteger(end)) {
          return null;
        }

        if (start > end) {
          const temp = start;
          start = end;
          end = temp;
        }

        if (start < 1 || end > maxPage) {
          return null;
        }

        for (let page = start; page <= end; page += 1) {
          numbers.add(page);
        }

        continue;
      }

      if (!/^\d+$/.test(token)) {
        return null;
      }

      const value = Number(token);
      if (!Number.isInteger(value) || value < 1 || value > maxPage) {
        return null;
      }

      numbers.add(value);
    }

    return Array.from(numbers).sort((a, b) => a - b);
  }

  function formatPageSelection(numbers) {
    const sorted = Array.from(new Set(numbers || [])).sort((a, b) => a - b);
    if (!sorted.length) {
      return "";
    }

    const parts = [];
    let start = sorted[0];
    let end = sorted[0];

    for (let index = 1; index < sorted.length; index += 1) {
      const current = sorted[index];

      if (current === end + 1) {
        end = current;
      } else {
        parts.push(start === end ? String(start) : `${start}-${end}`);
        start = current;
        end = current;
      }
    }

    parts.push(start === end ? String(start) : `${start}-${end}`);
    return parts.join(",");
  }

  function interpolate(template, values) {
    return Object.entries(values || {}).reduce(
      (message, [key, value]) => message.replaceAll(`:${key}`, String(value)),
      String(template || "")
    );
  }

  function stripExtension(name) {
    return name.replace(/\.[^/.]+$/, "");
  }

  function triggerDownload(url, fileName) {
    const anchor = document.createElement("a");
    anchor.href = url;
    anchor.download = fileName || "download";
    document.body.appendChild(anchor);
    anchor.click();
    anchor.remove();
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
