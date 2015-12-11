<?php

namespace Ghi\Equipamiento;

use Illuminate\Support\Facades\DB;

trait ManageDatabaseTransactions
{
    /**
     * Inicia una transaccion en un modelo.
     * 
     * @return void
     */
    protected function beginTransaction()
    {
        DB::connection($this->connection)->beginTransaction();
    }

    /**
     * Aplica una transaccion en un modelo.
     * 
     * @return void
     */
    protected function commitTransaction()
    {
        DB::connection($this->connection)->commit();
    }

    /**
     * Revierte una transaccion en un modelo.
     * 
     * @return void
     */
    protected function rollbackTransaction()
    {
        DB::connection($this->connection)->rollback();
    }
}
