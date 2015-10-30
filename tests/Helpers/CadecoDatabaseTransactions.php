<?php

trait CadecoDatabaseTransactions
{
    /**
     * @before
     */
    public function beginCadecoTransaction()
    {
        $this->app->make('db')->connection('cadeco')->beginTransaction();

        $this->beforeApplicationDestroyed(function () {
            $this->app->make('db')->connection('cadeco')->rollback();
        });
    }
}
