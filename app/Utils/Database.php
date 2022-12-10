<?php

namespace App\Utils;

use \PDO;
use \PDOException;
use PDOStatement;

class Database {

	/**
	 * Host de conexão com o banco de dados
	 * @var string
	 */
	private static $host;

	/**
	 * Nome do banco de dados
	 * @var string
	 */
	private static $name;

	/**
	 * Usuário do banco
	 * @var string
	 */
	private static $user;

	/**
	 * Senha de acesso ao banco de dados
	 * @var string
	 */
	private static $pass;

	/**
	 * Porta de acesso ao banco
	 * @var integer
	 */
	private static $port;

	/**
	 * Nome da tabela a ser manipulada
	 * @var string
	 */
	private $table;

	/**
	 * Instancia de conexão com o banco de dados
	 * @var PDO
	 */
	private $connection;

	/**
	 * Define a tabela e instancia e conexão
	 * @param string $table
	 */
	public function __construct(string $table = null) {
		$this->table = $table;
		$this->setConnection();
	}

	/**
	 * Método responsável por configurar a classe
	 * @param  string  $host
	 * @param  string  $name
	 * @param  string  $user
	 * @param  string  $pass
	 * @param  integer $port
	 * 
	 * @return void
	 */
	public static function config($host, $name, $user, $pass, $port = 5432): void {
		self::$host = $host;
		self::$name = $name;
		self::$user = $user;
		self::$pass = $pass;
		self::$port = $port;
	}

	/**
	 * Método responsável por criar uma conexão com o banco de dados
	 * @return void
	 */
	private function setConnection(): void {
		// TENTA CRIAR UMA NOVA CONEXÃO PDO
		try {
			$this->connection = new PDO('pgsql:host='.self::$host.';dbname='.self::$name.';port='.self::$port, self::$user, self::$pass);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e) {
			die('ERROR: '.$e->getMessage());
		}
	}

	/**
	 * Get conexão atual
	 * @return PDO
	 */
	public function getConnection(): PDO {
		return $this->connection;
	}

	/**
	 * Desativa o auto-commit da instancia
	 * @return void
	 */
	public function beginTransaction(): void {
		$this->connection->beginTransaction();
	}

	/**
	 * Salva as alterações na instancia
	 * @return void
	 */
	public function commit(): void {
		$this->connection->commit();
	}

	/**
	 * Revverte as alterações na instancia
	 * @return void
	 */
	public function rollBack(): void {
		$this->connection->rollBack();
	}

	/**
	 * Método responsável por executar queries dentro do banco de dados
	 * @param  string $query
	 * @param  array  $params
	 * 
	 * @return \PDOStatement|bool
	 */
	public function execute(string $query, array $params = []): mixed { 
		try {
			// EXECUTA A QUERY
			$statement = $this->connection->prepare($query);
			$statement->execute($params);

			// RETORNA O STATMENT
			return $statement;

		} catch (PDOException $e) {
			die('ERROR: '.$e->getMessage());
		}
	}

	/**
	 * Método responsável por inserir dados no banco
	 * @param  array $values [ field => value ]
	 * @param  boolean $returnId
	 * 
	 * @return integer|void ID inserido
	 */
	public function insert(array $values, bool $returnId = true): mixed {
		// DADOS DA QUERY
		$fields = array_keys($values);
		$binds  = array_pad([], count($fields), '?');

		// MONTA A QUERY
		$query = 'INSERT INTO '.$this->table.'('.implode(',', $fields).') VALUES ('.implode(',', $binds).')';

		// EXECUTA O INSERT
		$this->execute($query, array_values($values));

		// NÃO RETORNA O ID
		if ($returnId === false) {
			return null;
		}
		// RETORNA O ID INSERIDO
		return $this->connection->lastInsertId();
	}

	/**
     * Método responsavel por consultar toda a tabela
     * @param string $table
	 * @param string $fields
	 * 
	 * @return array
     */
    public function find(string $table, string $fields = '*'): array {
		$stmt = $this->execute("SELECT $fields FROM $table");

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	/**
	 * Método responsável por executar uma consulta no banco
	 * @param  string $where  condição
	 * @param  string $order  ordem
	 * @param  string $limit  limite
	 * @param  string $fields campos
	 * 
	 * @return \PDOStatement
	 */
	public function select($where = null, $order = null, $limit = null, $fields = '*'): PDOStatement {
		// DADOS DA QUERY
		$where = !is_null($where) ? 'WHERE '.$where : '';
		$order = !is_null($order) ? 'ORDER BY '.$order : '';
		$limit = !is_null($limit) ? 'LIMIT '.$limit : '';

		// MONTA A QUERY
		$query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit;

		// EXECUTA A QUERY
		return $this->execute($query);
	}

	/**
	 * Método responsável por executar atualizações no banco de dados
	 * @param  string $where
	 * @param  array  $values [ field => value ]
	 * 
	 * @return boolean
	 */
	public function update(string $where, array $values): bool {
		// DADOS DA QUERY
		$fields = array_keys($values);

		// MONTA A QUERY
		$query = 'UPDATE '.$this->table.' SET '.implode('=?,', $fields).'=? WHERE '.$where;

		// EXECUTAR A QUERY
		$this->execute($query, array_values($values));

		// RETORNA SUCESSO
		return true;
	}

	/**
	 * Método responsável por excluir dados do banco
	 * @param  string $where
	 * 
	 * @return boolean
	 */
	public function delete(string $where): bool {
		// MONTA A QUERY
		$query = 'DELETE FROM '.$this->table.' WHERE '.$where;

		// EXECUTA A QUERY
		$this->execute($query);

		// RETORNA SUCESSO
		return true;
	}
}
