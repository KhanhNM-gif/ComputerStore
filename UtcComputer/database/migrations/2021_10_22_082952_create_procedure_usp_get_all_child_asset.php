<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateProcedureUspGetAllChildAsset extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `usp_Asset_GetAllChild`;
        CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_Asset_GetAllChild`(AsssetID bigint)
        BEGIN
            with recursive cte (id, parent_id) as (
            select 
                id,
                asset_parent_id parent_id
            from asset
            where id = AsssetID
            union all
            select
                p.id,
                p.asset_parent_id parent_id
            from asset p
            inner join cte
                on p.asset_parent_id = cte.id
        )
        select * from asset
        WHERE asset.id IN (SELECT id FROM cte);
        END";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}