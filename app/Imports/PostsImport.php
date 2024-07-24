<?php

namespace App\Imports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\ToModel;

class PostsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row[3]);
        return new Post([
            // 'id' => $row[0],
            'title' => $row[1],
            'slug' => $row[2],
            'content' => $row[3],
            'description' => $row[4],
            // 'image' => $row[5],
             'posted' => $row[6],
             'category_id' => $row[7],
             'user_id' => 1,
        ]);
    }
}
