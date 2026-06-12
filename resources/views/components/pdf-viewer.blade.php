@props(['url', 'showDownload' => true])

<style>
    /* Hide browser's default PDF controls */
    ::-webkit-scrollbar-button,
    ::-webkit-scrollbar-thumb,
    ::-webkit-scrollbar-track {
        display: none;
    }

    /* Hide PDF.js default toolbar */
    .pdf-container .pdfjs-toolbar {
        display: none !important;
    }

    /* Hide any download buttons that might be injected by the browser */
    embed[type="application/pdf"] {
        position: relative;
    }

    embed[type="application/pdf"]::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        width: 40px;
        height: 40px;
        background-color: rgba(0,0,0,0.5);
        z-index: 9999;
    }

    /* PDF viewer styles */
    #pdf-viewer {
        background-color: #525659;
        height: calc(100vh - 200px);
        min-height: 500px;
        overflow: auto;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 10px;
        position: relative;
    }

    #pdf-canvas {
        background-color: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        max-width: 100%;
    }

    /* Premium feature overlay */
    #premium-feature-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.75);
        z-index: 50;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    #premium-feature-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .premium-feature-content {
        background-color: white;
        border-radius: 0.5rem;
        max-width: 400px;
        width: 100%;
        padding: 1.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    @media (max-width: 768px) {
        #pdf-viewer {
            height: calc(100vh - 250px);
            min-height: 400px;
        }
    }
</style>

<div class="w-full flex flex-col">
    <!-- PDF.js viewer container -->
    <div class="pdf-container flex-1 relative">
        <!-- Custom toolbar -->
        <div class="pdf-toolbar bg-gray-800 text-white p-2 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <!-- Page navigation -->
                <button id="prev-page" class="p-1 rounded hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <span id="page-num" class="text-sm">1</span>
                <span class="text-sm">/</span>
                <span id="page-count" class="text-sm">1</span>
                <button id="next-page" class="p-1 rounded hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <div class="flex items-center space-x-2">
                <!-- Zoom controls -->
                <button id="zoom-out" class="p-1 rounded hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
                <span id="zoom-level" class="text-sm">100%</span>
                <button id="zoom-in" class="p-1 rounded hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Download button (shown for all users) -->
                <a href="{{ $url }}" download class="p-1 rounded hover:bg-gray-700 ml-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- PDF canvas container -->
        <div id="pdf-viewer">
            <canvas id="pdf-canvas"></canvas>
        </div>

        <!-- Loading indicator -->
        <div id="loading-indicator" class="absolute inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
            <div class="text-white text-center">
                <svg class="animate-spin h-10 w-10 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p>Loading PDF...</p>
                <p class="text-xs mt-2">If the PDF doesn't load, <a href="{{ $url }}" target="_blank" class="text-blue-300 hover:text-blue-200 underline">click here to open it directly</a>.</p>
            </div>
        </div>
    </div>


</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    // Ensure PDF.js is loaded
    if (typeof pdfjsLib === 'undefined') {
        console.error('PDF.js library not loaded!');
        document.getElementById('loading-indicator').innerHTML = `
            <div class="text-white text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p>PDF.js library failed to load. Please try refreshing the page.</p>
                <a href="{{ $url }}" target="_blank" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Open PDF directly
                </a>
            </div>
        `;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Set worker path for PDF.js
        if (typeof pdfjsLib !== 'undefined') {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        // PDF viewer variables
        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        let scale = 1.0;
        let canvas = document.getElementById('pdf-canvas');
        let ctx = canvas.getContext('2d');
        let loadingIndicator = document.getElementById('loading-indicator');



        // Load the PDF
        const loadPDF = async () => {
            try {
                // Show loading indicator
                loadingIndicator.style.display = 'flex';

                // Load the PDF document
                const pdfUrl = '{{ $url }}';
                console.log('Loading PDF from URL:', pdfUrl);

                // Create a binary data loader for the PDF
                const loadingTask = pdfjsLib.getDocument({
                    url: pdfUrl,
                    withCredentials: true,
                    cMapUrl: 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/cmaps/',
                    cMapPacked: true,
                });

                pdfDoc = await loadingTask.promise;

                // Update page count
                document.getElementById('page-count').textContent = pdfDoc.numPages;

                // Initial render of the first page
                await renderPage(pageNum);

                // Hide loading indicator
                loadingIndicator.style.display = 'none';

                console.log('PDF loaded successfully');
            } catch (error) {
                console.error('Error loading PDF:', error);

                // Create a more detailed error message
                let errorMessage = 'Error loading PDF. Please try again later.';
                if (error.name === 'MissingPDFException') {
                    errorMessage = 'The PDF file could not be found. Please check the URL.';
                } else if (error.name === 'InvalidPDFException') {
                    errorMessage = 'The PDF file is invalid or corrupted.';
                } else if (error.name === 'UnexpectedResponseException') {
                    errorMessage = 'Unexpected server response. The PDF might not be accessible.';
                } else if (error.message) {
                    errorMessage = `Error: ${error.message}`;
                }

                // Display the error message
                loadingIndicator.innerHTML = `
                    <div class="text-white text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p>${errorMessage}</p>
                        <p class="text-xs mt-2">Technical details: ${error.name || 'Unknown error'}</p>
                        <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Reload Page
                        </button>
                    </div>
                `;

                // Log additional details for debugging
                console.log('PDF URL:', '{{ $url }}');
                console.log('Error name:', error.name);
                console.log('Error message:', error.message);
                console.log('Error stack:', error.stack);
            }
        };

        // Render the page
        const renderPage = async (num) => {
            pageRendering = true;

            try {
                // Get the page
                const page = await pdfDoc.getPage(num);

                // Set scale
                const viewport = page.getViewport({ scale: scale });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                // Render PDF page
                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                await page.render(renderContext).promise;

                pageRendering = false;

                if (pageNumPending !== null) {
                    // New page rendering is pending
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }

                // Update page counter
                document.getElementById('page-num').textContent = num;
            } catch (error) {
                console.error('Error rendering page:', error);
                pageRendering = false;
            }
        };

        // Queue the page rendering
        const queueRenderPage = (num) => {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        };

        // Go to previous page
        const onPrevPage = () => {
            if (pageNum <= 1) {
                return;
            }
            pageNum--;
            queueRenderPage(pageNum);
        };

        // Go to next page
        const onNextPage = () => {
            if (pageNum >= pdfDoc.numPages) {
                return;
            }
            pageNum++;
            queueRenderPage(pageNum);
        };

        // Zoom in
        const zoomIn = () => {
            scale += 0.1;
            document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
            queueRenderPage(pageNum);
        };

        // Zoom out
        const zoomOut = () => {
            if (scale <= 0.2) return;
            scale -= 0.1;
            document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
            queueRenderPage(pageNum);
        };

        // Add event listeners
        document.getElementById('prev-page').addEventListener('click', onPrevPage);
        document.getElementById('next-page').addEventListener('click', onNextPage);
        document.getElementById('zoom-in').addEventListener('click', zoomIn);
        document.getElementById('zoom-out').addEventListener('click', zoomOut);

        // Start loading the PDF
        loadPDF();
    });
</script>
@endpush
