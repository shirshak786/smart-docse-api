<?php

namespace Modules\Meta\Database\Seeders;

use App\Models\Meta;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class MetaDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $meta = factory(Meta::class)->make();
        $meta->route = 'home';
        $meta->save();

        $meta = factory(Meta::class)->make();
        $meta->route = 'about';
        $meta->save();

        $meta = factory(Meta::class)->make();
        $meta->route = 'blog';
        $meta->save();

        $meta = factory(Meta::class)->make();
        $meta->route = 'contact';
        $meta->save();
    }
}
