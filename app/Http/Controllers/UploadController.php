<?php

namespace App\Http\Controllers;

use App\Data;
use App\DataHelper;
use Carbon\Carbon;
use Cerbero\JsonObjects\JsonObjects;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public $previousRecordUidStrs = null;

    public function index()
    {
        return view('admin.upload');
    }

    public function apiUploadAjax(Request $request)
    {
        if ($request->hasFile('file')) {
            $extension = $request->file('file')->getClientOriginalExtension();
            $containsExtension = in_array(strtolower($extension), ['json']);

            if ($containsExtension) {

                $originalfileName = $request->file('file')->getClientOriginalName();
                $fileName         = Str::replaceLast('.json', '', $originalfileName);
                $fileName         = $fileName . "_" . Str::slug(Carbon::now()) . rand(11111, 99999) . '.' . $extension;

                $result = $request->file('file')->move('files/', $fileName);

                // try {
                JsonObjects::from($result->getRealPath())
                    ->chunk(100, function (array $objects) {
                        $this->processObjects($objects);
                    });
                return response()->json(['success' => true]);
                // } catch (Exception $e) {
                // Log::error($e->getMessage());
                // return response()->json(['success' => false, 'error' => "hibás fajl"], 500);
                // }
            }

            return response()->json(['success' => false, 'error' => "hibás fajl"], 500);
        }
    }

    public function processObjects($jsonArray)
    {
        $inserts       = [];
        $insertHelpers = [];

        $exists = [];

        foreach ($jsonArray as $record) {
            $exists[] = $record['id'];
        }

        $previousRecordUidStrs = Data::select('id', 'id_str')->whereIn('id_str', $exists)->get();

        foreach ($jsonArray as $record) {

            $match = $previousRecordUidStrs->where('id_str', $record['id'])->first();

            $issuedDate = null;
            $issued     = Arr::get($record, 'issued.date-parts.0.0', null);

            $data = [
                'id_str'               => Arr::get($record, 'id', ''),
                'title'                => Arr::get($record, 'title', ''),
                'type'                 => Arr::get($record, 'type', ''),
                'doi'                  => Arr::get($record, 'DOI', ''),
                'abstract'             => Arr::get($record, 'abstract', ''),
                'journal_abbreviation' => Arr::get($record, 'journalAbbreviation', ''),
                'container_title'      => Arr::get($record, 'container-title', ''),
                'url'                  => Arr::get($record, 'URL', ''),
                'source'               => Arr::get($record, 'source', ''),
                'issn'                 => Arr::get($record, 'ISSN', ''),
                'issue'                => Arr::get($record, 'issue', ''),
                'language'             => Arr::get($record, 'language', ''),
                'note'                 => Arr::get($record, 'note', ''),
                'page'                 => Arr::get($record, 'page', ''),
                'volume'               => (int) Arr::get($record, 'volume', 0),
                'issued'               => $issued,
                'authors'              => json_encode(Arr::get($record, 'author', '[]'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ];

            $dataHelper = $this->sluggify($data);

            if ($match) {
                $data['updated_at']       = Carbon::now();
                $helperData['updated_at'] = $data['updated_at'];
                Data::where('id_str', $record['id'])->update($data);
                DataHelper::where('id_str', $record['id'])->update($dataHelper);
            }

            if (!$match) {
                $data['created_at']       = Carbon::now();
                $dataHelper['created_at'] = Carbon::now();
                $inserts[]                = $data;
                $insertHelpers[]          = $dataHelper;
            }
        }

        Data::insert($inserts);
        DataHelper::insert($insertHelpers);
    }

    /**
     *
     * @param mixed $data
     * @return mixed
     */
    protected function sluggify($data)
    {
        $jsons   = ['authors'];
        $slugs   = ['title', 'type', 'container_title', 'language', 'note', 'abstract', 'source', 'journal_abbreviation'];
        $removes = ['original'];

        foreach ($data as $key => $value) {
            if (in_array($key, $slugs)) {
                $data[$key] = Str::slug($value, ' ');
            }
            if (in_array($key, $removes)) {
                unset($data[$key]);
            }
            if (in_array($key, $jsons)) {
                $authors     = [];
                $nameObjects = json_decode($value);

                if ($nameObjects) {
                    continue;
                }

                foreach ($nameObjects as $nameObj) {
                    $names = [];
                    foreach ($nameObj as $k => $nameValue) {
                        array_push($names, Str::slug($nameValue, ' '));
                    }
                    array_push($authors, join(" ", $names) . ", " . join(' ', array_reverse($names)));
                }

                $data[$key] = join('|', $authors);
            }
        }

        return $data;
    }
}
