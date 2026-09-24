<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ basename($ebookFile->file_path) }}</title>
    <style>
        :root {
            --pdf-bg:        #525659;
            --pdf-toolbar:   #323639;
            --pdf-btn:       #444;
            --pdf-btn-hover: #555;
            --pdf-text:      #eee;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            height: 100%;
            background: var(--pdf-bg);
            overflow: hidden;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            font-size: 13px;
            color: var(--pdf-text);
        }

        .pdf-viewer {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* ---------- Toolbar ---------- */
        .pdf-toolbar {
            flex: 0 0 auto;
            background: var(--pdf-toolbar);
            color: var(--pdf-text);
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 1px 0 rgba(0,0,0,.3);
        }

        .pdf-toolbar button {
            background: var(--pdf-btn);
            color: var(--pdf-text);
            border: 0;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            line-height: 1;
            transition: background .15s;
        }
        .pdf-toolbar button:hover  { background: var(--pdf-btn-hover); }
        .pdf-toolbar button:disabled { opacity: .4; cursor: not-allowed; }

        .pdf-toolbar .page-info {
            padding: 0 4px;
            user-select: none;
        }

        .pdf-toolbar .spacer { flex: 1; }

        .pdf-toolbar .title {
            opacity: .75;
            max-width: 40ch;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* ---------- Canvas area ---------- */
        .pdf-canvas-wrap {
            flex: 1 1 auto;
            overflow: auto;
            text-align: center;
            padding: 12px 0;
            scroll-behavior: smooth;
        }

        .pdf-canvas-wrap canvas {
            display: block;
            margin: 0 auto 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,.5);
            background: #fff;
        }

        /* ---------- Loading / error ---------- */
        .pdf-loading {
            color: var(--pdf-text);
            padding: 40px;
            text-align: center;
        }
        .pdf-loading.error { color: #fca5a5; }

        /* ---------- Dark scrollbar (optional) ---------- */
        .pdf-canvas-wrap::-webkit-scrollbar { width: 12px; height: 12px; }
        .pdf-canvas-wrap::-webkit-scrollbar-track { background: #3a3d40; }
        .pdf-canvas-wrap::-webkit-scrollbar-thumb { background: #5a5e63; border-radius: 6px; }
        .pdf-canvas-wrap::-webkit-scrollbar-thumb:hover { background: #6b7075; }
    </style>
</head>
<body>

    @php
        $isAdmin = str_starts_with((string) request()->route()->getName(), 'admin.');
        $streamHref = $isAdmin
            ? route('admin.ebook-files.stream', $ebookFile)
            : route('ebooks.stream', $ebookFile);
    @endphp

    <div class="pdf-viewer" id="pdfViewer" data-stream-url="{{ $streamHref }}">
        <div class="pdf-toolbar">
            <button type="button" id="pdfPrev"     title="Previous page (←)">‹</button>
            <span class="page-info">Page <span id="pdfNum">1</span> / <span id="pdfCount">?</span></span>
            <button type="button" id="pdfNext"     title="Next page (→)">›</button>

            <button type="button" id="pdfZoomOut"  title="Zoom out (-)">−</button>
            <button type="button" id="pdfZoomIn"   title="Zoom in (+)">+</button>
            <button type="button" id="pdfFit"      title="Fit width">Fit</button>

            <span class="spacer"></span>
            <span class="title" title="{{ basename($ebookFile->file_path) }}">
                {{ basename($ebookFile->file_path) }}
            </span>
        </div>

        <div class="pdf-canvas-wrap" id="pdfWrap">
            <div class="pdf-loading" id="pdfLoading">Loading PDF…</div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
    (function () {
        const root = document.getElementById('pdfViewer');
        if (!root) return;

        const url      = root.dataset.streamUrl;
        const wrap     = root.querySelector('#pdfWrap');
        const loading  = root.querySelector('#pdfLoading');
        const pageNumEl= root.querySelector('#pdfNum');
        const pageCntEl= root.querySelector('#pdfCount');
        const prevBtn  = root.querySelector('#pdfPrev');
        const nextBtn  = root.querySelector('#pdfNext');
        const zoomIn   = root.querySelector('#pdfZoomIn');
        const zoomOut  = root.querySelector('#pdfZoomOut');
        const fitBtn   = root.querySelector('#pdfFit');

        pdfjsLib.GlobalWorkerOptions.workerSrc =
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        let pdfDoc    = null;
        let pageNum   = 1;
        let scale     = 1.4;
        let rendering = false;
        let pending   = null;

        pdfjsLib.getDocument(url).promise
            .then(function (pdf) {
                pdfDoc = pdf;
                pageCntEl.textContent = pdf.numPages;
                loading.remove();
                renderPage(pageNum);
            })
            .catch(function (err) {
                loading.classList.add('error');
                loading.textContent = 'Failed to load PDF: ' + (err && err.message ? err.message : err);
            });

        function renderPage(num) {
            rendering = true;
            pdfDoc.getPage(num).then(function (page) {
                const viewport = page.getViewport({ scale: scale });
                const canvas   = document.createElement('canvas');
                const ctx      = canvas.getContext('2d');
                canvas.width   = viewport.width;
                canvas.height  = viewport.height;
                canvas.style.width  = viewport.width  + 'px';
                canvas.style.height = viewport.height + 'px';

                wrap.innerHTML = '';
                wrap.appendChild(canvas);

                page.render({ canvasContext: ctx, viewport: viewport })
                    .promise
                    .then(function () {
                        rendering = false;
                        if (pending !== null) {
                            const p = pending; pending = null;
                            renderPage(p);
                        }
                    });
            });

            pageNumEl.textContent = num;
            prevBtn.disabled = num <= 1;
            nextBtn.disabled = num >= pdfDoc.numPages;
        }

        function queueRender(num) {
            if (rendering) { pending = num; return; }
            renderPage(num);
        }

        prevBtn.addEventListener('click', function () {
            if (pageNum > 1) queueRender(--pageNum);
        });
        nextBtn.addEventListener('click', function () {
            if (pageNum < pdfDoc.numPages) queueRender(++pageNum);
        });
        zoomIn.addEventListener('click', function () {
            scale *= 1.2; queueRender(pageNum);
        });
        zoomOut.addEventListener('click', function () {
            scale = Math.max(0.3, scale / 1.2); queueRender(pageNum);
        });
        fitBtn.addEventListener('click', function () {
            pdfDoc.getPage(pageNum).then(function (page) {
                const base = page.getViewport({ scale: 1 });
                scale = (wrap.clientWidth - 24) / base.width;
                queueRender(pageNum);
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowRight' || e.key === 'PageDown') nextBtn.click();
            if (e.key === 'ArrowLeft'  || e.key === 'PageUp')   prevBtn.click();
            if (e.key === '+' || e.key === '=') zoomIn.click();
            if (e.key === '-')                  zoomOut.click();
            if (e.key === 'Escape') window.parent.postMessage('pdf-modal-close', '*');
        });

        // Ctrl/⌘ + scroll to zoom
        wrap.addEventListener('wheel', function (e) {
            if (!e.ctrlKey && !e.metaKey) return;
            e.preventDefault();
            if (e.deltaY < 0) zoomIn.click(); else zoomOut.click();
        }, { passive: false });
    })();
    </script>

</body>
</html>