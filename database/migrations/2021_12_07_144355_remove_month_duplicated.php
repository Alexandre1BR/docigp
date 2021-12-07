<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveMonthDuplicated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $toBeDeletedCongressmanBudgets = [
            2381,
            2329,
            2331,
            2332,
            2336,
            2340,
            2341,
            2345,
            2386,
            2349,
            2351,
            2354,
            2359,
            2362,
            2366,
            2371,
            2374,
            2397,
            2380,
        ];

        foreach ($toBeDeletedCongressmanBudgets as $id) {
            $count = DB::delete("delete from entries where congressman_budget_id={$id}");
            dump('Removendo as entries ' . $count);
            $count = DB::delete("delete from congressman_budgets where id={$id}");
            dump('Removendo o congressman_budgets ' . $count);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
/*


Id de congressman_budgets dos meses de novembro
php artisan docigp:entries:update-transport 2249
php artisan docigp:entries:update-transport 2240
php artisan docigp:entries:update-transport 2241
php artisan docigp:entries:update-transport 2242
php artisan docigp:entries:update-transport 2243
php artisan docigp:entries:update-transport 2244
php artisan docigp:entries:update-transport 2245
php artisan docigp:entries:update-transport 2248
php artisan docigp:entries:update-transport 2250
php artisan docigp:entries:update-transport 2268
php artisan docigp:entries:update-transport 2251
php artisan docigp:entries:update-transport 2252
php artisan docigp:entries:update-transport 2254
php artisan docigp:entries:update-transport 2253
php artisan docigp:entries:update-transport 2255
php artisan docigp:entries:update-transport 2261
php artisan docigp:entries:update-transport 2257
php artisan docigp:entries:update-transport 2258
php artisan docigp:entries:update-transport 2259
php artisan docigp:entries:update-transport 2260
php artisan docigp:entries:update-transport 2267
php artisan docigp:entries:update-transport 2264
php artisan docigp:entries:update-transport 2263
php artisan docigp:entries:update-transport 2270
php artisan docigp:entries:update-transport 2266
php artisan docigp:entries:update-transport 2269
php artisan docigp:entries:update-transport 2246
php artisan docigp:entries:update-transport 2274
php artisan docigp:entries:update-transport 2273
php artisan docigp:entries:update-transport 2276
php artisan docigp:entries:update-transport 2278
php artisan docigp:entries:update-transport 2293
php artisan docigp:entries:update-transport 2280
php artisan docigp:entries:update-transport 2283
php artisan docigp:entries:update-transport 2281
php artisan docigp:entries:update-transport 2284
php artisan docigp:entries:update-transport 2285
php artisan docigp:entries:update-transport 2282
php artisan docigp:entries:update-transport 2287
php artisan docigp:entries:update-transport 2289
php artisan docigp:entries:update-transport 2290
php artisan docigp:entries:update-transport 2294
php artisan docigp:entries:update-transport 2286
php artisan docigp:entries:update-transport 2295
php artisan docigp:entries:update-transport 2297
php artisan docigp:entries:update-transport 2298
php artisan docigp:entries:update-transport 2300
php artisan docigp:entries:update-transport 2301
php artisan docigp:entries:update-transport 2302
php artisan docigp:entries:update-transport 2304
php artisan docigp:entries:update-transport 2299
php artisan docigp:entries:update-transport 2305
php artisan docigp:entries:update-transport 2306
php artisan docigp:entries:update-transport 2308
php artisan docigp:entries:update-transport 2307
php artisan docigp:entries:update-transport 2309
php artisan docigp:entries:update-transport 2291
php artisan docigp:entries:update-transport 2303
php artisan docigp:entries:update-transport 2272
php artisan docigp:entries:update-transport 2292
php artisan docigp:entries:update-transport 2277
php artisan docigp:entries:update-transport 2288
php artisan docigp:entries:update-transport 2275
php artisan docigp:entries:update-transport 2247
php artisan docigp:entries:update-transport 2256
php artisan docigp:entries:update-transport 2265
php artisan docigp:entries:update-transport 2279
php artisan docigp:entries:update-transport 2271
php artisan docigp:entries:update-transport 2262
php artisan docigp:entries:update-transport 2296
php artisan docigp:entries:update-transport 2310


Meses duplicados (novembro)
Anderson alexandre 2381
Eliomar coelho 2329
Enfermeira Rejane 2331
Fábio Silva 2332
Flavio Serafini 2336
Franciane Motta 2338 2340
Giovani Ratinho 2341
Jair Bittencourt 2345
Luiz Martins 2386
Luiz Paulo 2349
Marcelo Dino 2351
Márcio Gualberto 2354
Mônica Francisco 2359
Renato Zaca 2362
Rodrigo Bacellar 2366
Sergio Fernandes 2371
Tia Ju 2374
Wellington José 2397
Zeidan Lula 2380


2381,2329,2331,2332,2336,2340,2341,2345,2386,2349,2351,2354,2359,2362,2366,2371,2374,2397,2380


Select c.name, duplicados.congressman_legislature_id, duplicados.created_at, max(cb2.id) from
congressman_legislatures cl, congressmen c, congressman_budgets cb2,

(select cb.congressman_legislature_id, count(cb.budget_id)
from
congressman_budgets cb
group by cb.congressman_legislature_id, cb.created_at
having count(cb.budget_id) > 1
order by cb.created_at desc) duplicados



where cl.congressman_id = c.id and
duplicados.congressman_legislature_id = cl.id and
cb2.congressman_legislature_id = duplicados.congressman_legislature_id and
cb2.created_at = duplicados.created_at
group by c.name, duplicados.congressman_legislature_id, duplicados.created_at
order by c.name





*/
