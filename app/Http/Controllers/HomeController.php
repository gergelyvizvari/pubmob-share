<?php

namespace App\Http\Controllers;

use App\Data;
use App\DataHelper;
use Carbon\Carbon;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    protected $types = [
        'id_str'               => '=',
        'type'                 => 'like',
        'issn'                 => 'like',
        'issue'                => 'like',
        'language'             => 'like',
        'note'                 => 'like',
        'title'                => 'like',
        'authors'              => 'like',
        'doi'                  => 'like',
        'abstract'             => 'like',
        'url'                  => 'like',
        'source'               => 'like',
        'journal_abbreviation' => 'like',
        'container_title'      => 'like',
        'issued'               => '=',
    ];

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', [
            'auth_user' => Auth::user()
        ]);
    }

    public function deleteItem()
    {
        $id_str = request()->post('id_str', '');

        $data = Data::where(['id_str' => $id_str])->delete();
        $dataHelper = DataHelper::where(['id_str' => $id_str])->delete();

        return response()->json([
            'success' => true,
            'data'    => [],
        ]);
    }

    private function getOperator($field, $negation)
    {
        $operator = $this->types[$field];

        if ($negation && $operator == 'like') {
            return 'not ' . $operator;
        }

        if ($negation && $operator == '=') {
            return '!' + $operator;
        }

        return $operator;
    }

    private function enclose($value, $field)
    {
        $operator = $this->types[$field];

        if ($operator == 'like') {
            return '%' . $value . '%';
        }

        return $value;
    }

    public function apiGetData()
    {
        $results = $this->getResults();

        return response()->json([
            'success' => true,
            'data'    => $results,
        ]);
    }

    protected function getResults()
    {
        $filters       = request()->get('filters', []);
        $data          = DataHelper::select(['id_str']);
        $filterCounter = 0;

        foreach ($filters as $filter) {
            if ($filter['field'] != null && $filter['value'] != '' && $filter['value'] != null) {

                $field = $filter['field'];
                $value = $this->enclose(Str::slug($filter['value'], ' '), $filter['field']);

                if ($filter['operator'] == 'and') {
                    $operator = $this->getOperator($filter['field'], false);
                    $data->where($field, $operator, $value);
                };

                if ($filter['operator'] == 'or') {
                    $operator = $this->getOperator($filter['field'], false);
                    $data->orWhere($field, $operator, $value);
                };

                if ($filter['operator'] == 'not') {
                    $operator = $this->getOperator($filter['field'], true);
                    $data->where($field, $operator, $value);
                };

                $filterCounter++;
            }
        }

        if ($filterCounter > 0) {
            return Data::query()
                ->select([
                    'id_str',
                    'type',
                    'issn',
                    'issue',
                    'language',
                    'note',
                    'title',
                    'authors',
                    'issued',
                    'issued_date',
                    'volume',
                    'page',
                    'doi',
                    'abstract',
                    'url',
                    'source',
                    'container_title',
                    'journal_abbreviation',
                ])
                ->whereIn('id_str', $data->get()->toArray())
                ->get();
        }

        return [];
    }
}
