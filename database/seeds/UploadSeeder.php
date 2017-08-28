<?php

use Illuminate\Database\Seeder;

class UploadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => '1n1d17_water',
            'source' => 'images/banners/1n1d17-water-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => '1n1d17_vision',
            'source' => 'images/banners/1n1d17-vision-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => '1n1d17_vision2',
            'source' => 'images/banners/1n1d17-vision2-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => '1n1d17_vision3',
            'source' => 'images/banners/1n1d17-vision3-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => '1n1d17_missionary',
            'source' => 'images/banners/1n1d17-missionary-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => '1n1d17_speak',
            'source' => 'images/banners/1n1d17-speak-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => 'gen_ban_1',
            'source' => 'images/banners/gen-ban-1-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => 'gen_ban_2',
            'source' => 'images/banners/gen-ban-2-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => 'gen_ban_3',
            'source' => 'images/banners/gen-ban-3-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => 'gen_ban_4',
            'source' => 'images/banners/gen-ban-4-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => 'gen_ban_5',
            'source' => 'images/banners/gen-ban-5-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => 'gen_ban_6',
            'source' => 'images/banners/gen-ban-6-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => 'gen_ban_7',
            'source' => 'images/banners/gen-ban-7-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => 'gen_ban_8',
            'source' => 'images/banners/gen-ban-8-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => 'gen_ban_9',
            'source' => 'images/banners/gen-ban-9-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => 'gen_ban_10',
            'source' => 'images/banners/gen-ban-10-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => 'gen_ban_11',
            'source' => 'images/banners/gen-ban-11-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });

        factory(App\Models\v1\Upload::class, 'banner')->create([
            'name' => 'gen_ban_12',
            'source' => 'images/banners/gen-ban-12-2560x800.jpg'
        ])->each(function ($u) {
            $u->tag(['Fundraiser', 'User', 'Group', 'Campaign']);
        });
    }
}
