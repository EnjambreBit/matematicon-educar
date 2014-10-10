<?php
namespace Edufw\db;
use Edufw\core\EException;

/**
 * Representa una transaccion de base de datos
 * Ejemplo:
 *  $transaction = new EDbTransaction();
 *  $transaction->newTransaction();
 *  try {
 *      //Dentro del try ejecutamos metodos de instancia que involucren
 *      //operaciones sobre base de datos
 *      $user->save();
 *      $invent->delete();
 *      ....
 *      //Cuando finalizamos, realizamos commit de transaccion
 *      $transaction->endTransaction();
 *  } catch (Exception $e) {
 *      $transaction->cancelTransaction();
 *  }
 *
 * @name ETransaction
 * @package lib\components\active_record
 * @version 20110110
 * @author gseip
 */
class EDbTransaction extends EDbSQL {
	private $_active = FALSE;

	/**
	 * Constructor.
	 * @param EConnection $connection la conexion asociada con esta conexion
	 */
	public function __construct($edbConnection=NULL)	{
            $this->setEDbConnection($edbConnection);
	}

        public function execute() {   }


        /**
         * Comienza (begin) una nueva transaccion de base de datos
         */
        public final function newTransaction() {
            if (!$this->_active)    {
                $this->_EDbConnection->getConnection()->beginTransaction();
                $this->_active = TRUE;
            } else {   throw new EException('[EDbTransaction, newTransaction()] Hay una transaccion iniciada. Finalize la transaccion actual');   }
        }

	/**
	 * Finaliza (commit) una transaccion de base de datos
	 */
	public function endTransaction()    {
            if ($this->_active) {
                $this->_EDbConnection->getConnection()->commit();
                $this->_active = FALSE;
            } else {
                throw new EException("[ETransaction, endTransaction()] No hay transaccion activa. No es posible realizar commit");  }
	}

        /**
         * Cancela (rollback) una transaccion de base de datos
         */
        public final function cancelTransaction() {
            if($this->_active)	{
                $this->_EDbConnection->getConnection()->rollBack();
                $this->_active = FALSE;
            } else {
                throw new EException("[ETransaction, cancelTransaction()] No hay transaccion activa. No es posible realizar rollback");  }

        }
}