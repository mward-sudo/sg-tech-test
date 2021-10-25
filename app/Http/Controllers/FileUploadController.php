<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Homeowner;
use App\Names\Parser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    public function __construct(
        private Request $request,
        private Parser $name_parser,
        private Homeowner $home_owner,
    ) {
    }

    /**
     * Generate Upload View
     *
     * @return void
     */
    public  function dropzoneUi()
    {
        return view('upload-view');
    }

    /**
     * File Upload Method
     *
     * @return void
     */
    public function dropzoneFileUpload()
    {
        $file = $this->request->file('file');
        $time = time();

        $filename = "{$time}.{$file->extension()}";
        $file->move(public_path('upload'), $filename);

        $this->parseFile($filename);

        return response()->json(['success' => $filename]);
    }

    private function parseFile(string $filename)
    {
        $upload_time = Carbon::now();

        $file = public_path('upload') . "/{$filename}";

        /** Open the data file */
        $csv_data_file = fopen($file, 'r');

        $skipLine = true;

        /** Loop over each line of the data file */
        while (!feof($csv_data_file)) {
            /** Parses the line of CSV data in to an array */
            $name = fgetcsv($csv_data_file)[0];
            /** Skips the first line only */
            if ($skipLine) {
                $skipLine = false;
                continue;
            }
            /** Creates a new instance of the NameParser */
            $parser_class = get_class($this->name_parser);
            $parser = new $parser_class($name);

            $parserRsults = $parser->parse();
            foreach ($parserRsults as $p) {
                $homeOwner = new Homeowner();
                $homeOwner->title = $p['title'];
                $homeOwner->first_name = $p['first_name'];
                $homeOwner->initial = $p['initial'];
                $homeOwner->last_name = $p['last_name'];
                $homeOwner->upload_file = $filename;
                $homeOwner->uploaded_at = $upload_time;
                $homeOwner->save();
            }
        }
        /** Close the data file */
        fclose($csv_data_file);
    }
}
