<x-layout>
    <x-slot name="title">Upload your home owners file</x-slot>

    <x-slot name="head">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.js"></script>
    </x-slot>

    <div class="grid justify-center items-center h-screen">
        <div class="shadow-lg text-center p-8 self-center justify-self-center rounded-lg bg-gray-50">
            <h1 class="text-2xl">Upload your home owner names file</h1>
            <p>Click the icon or drag &amp; drop your CSV below.</p>
            <form action="{{ route('dropzoneFileUpload') }}" id="file-upload" class="dropzone border-0" enctype="multipart/form-data" style="border: none; background-color: inherit;">
                @csrf
                <div class="mt-6 border-4 border-dashed border-gray-300 bg-white p-6 dz-message">
                    <img src="{{ asset('images/arrow-upload-icon.png')}}" class="mx-auto filter grayscale hover:filter-none transition-all" />
                </div>
            </form>
        </div>

        <div id="uploads-list"></div>
    </div>

    <script>
        function getUploadsList(params) {
            var xhttp = new XMLHttpRequest()
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("uploads-list").innerHTML = this.responseText
                }
            }
            xhttp.open("GET", "/api/uploads/", true)
            xhttp.send()
        }
        getUploadsList()

        Dropzone.options.fileUpload = {
            init: function() {
                this.on("success", function() { getUploadsList() })
            }
        };
    </script>
</x-layout>
