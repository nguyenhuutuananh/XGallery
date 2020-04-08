<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JavMovies extends Model
{
    public function idols()
    {
        $query = DB::table('jav_idols AS idol');
        $query
            ->leftJoin('jav_movies_xrefs as xref', 'xref.xref_id', '=', 'idol.id')
            ->where('xref.xref_type', '=', 'idol')
            ->where('xref.movie_id', '=', $this->id)
            ->select('idol.*');

        return $query->get();
    }

    public function genres()
    {
        $query = DB::table('jav_genres AS idol');
        $query
            ->leftJoin('jav_movies_xrefs as xref', 'xref.xref_id', '=', 'idol.id')
            ->where('xref.xref_type', '=', 'genre')
            ->where('xref.movie_id', '=', $this->id)
            ->select('idol.*');

        return $query->get();
    }
}
