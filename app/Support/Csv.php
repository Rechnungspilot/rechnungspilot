<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Csv
{
    protected $delimiter = ";";
    protected $enclosure = '"';
    protected $escape_char = "\\";
    protected $filename;
    protected $header;
    protected $rows = [];
    protected $source;
    protected $callback;
    protected $collection;

    public function file(string $filename) : self
    {
        $this->filename = $filename;
        return $this;
    }

    public function header(array $header) : self
    {
        $this->header = $header;
        return $this;
    }

    public function collection(Collection $collection) : self
    {
        $this->collection = $collection;
        return $this;
    }

    public function callback(Callable $callback) : self
    {
        $this->callback = $callback;
        return $this;
    }

    public function export()
    {
        $headers = array(
            'Content-Type'        => 'text/csv',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Disposition' => 'attachment; filename=' . $this->filename . '.csv',
            'Expires'             => '0',
            'Pragma'              => 'public',
        );

        $additional_param = [];
        $response = new StreamedResponse( function() use($additional_param) {
            $this->make($this->open());
        }, 200, $headers);

        return $response->send();
    }

    public function save(string $path) {
        $this->make($this->open($path));
    }

    protected function open(string $path = 'php://output')
    {
        return fopen($path, 'w');
    }

    public function row(array $row) : void
    {
        $this->rows[] = $row;
    }

    protected function put($handle, array $row)
    {
        return fputcsv($handle, $row, $this->delimiter, $this->enclosure, $this->escape_char);
    }

    protected function make($handle)
    {
        if ($this->header) {
            $this->put($handle, $this->header);
        }

        foreach ($this->rows as $key => $row) {
            $this->put($handle, $row);
        }

        if ($this->collection) {
            foreach ($this->collection as $item) {
                $expo_arr = call_user_func($this->callback, $item);
                $this->put($handle, $expo_arr);
            }
        }

        fclose($handle);
    }

}

?>