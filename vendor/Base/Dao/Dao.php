<?php
/**
 * Abstract factory/mapper. 
 * This is an implementation of pattern Data Access Object
 */

namespace Base\Dao;

use Zend\Db\Sql\Sql;
use Base\Exception;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Db\ResultSet\ResultSet;

abstract class Dao implements \Zend\Db\Adapter\AdapterAwareInterface {

	static public $useCache = true;

	/**
	 * @var Zend\Db\Adapter\Adapter
	 */
	protected $db;

	/**
	 * list of a table's fields.
	 * Use associative array to map fields to an business object properties
	 * 'object_property_name'=>'table_column_name'
	 *
	 * @var array
	 */
	private $fields = array();

	/**
	 * name of entity table in DB
	 * @var string
	 */
	protected $table;

	/**
	 * name of a primary key field in DB
	 * to remove, update and delete records
	 * @var string
	 */
	protected $primKey;

	/**
	 * @var \ArrayAccess 
	 */
	private $cache;

	/**
	 * @var Zend\Db\Sql\Sql
	 */
	protected $sql;

	///////////////////////////////////////////  Mappers ///////////////////////////////

	public function __construct($table, $primKey, array $fields) {
		$this->table = $table;
		$this->primKey = $primKey;
		$this->fields = $fields;
	}

	/**
	 * Return empty object to populate in the class's methods
	 * @return Object
	 */
	abstract function getNewEntity();

	/**
	 * Default map to entity
	 * Returns populated $entity from $data
	 *
	 * @param ArrayObject $result
	 * @return Object
	 */
	public function mapToEntity($result, $entity = null) {
		$id = $result[$this->primKey];
		if ($this->isInCache($id)) {
			return $this->getFromCache($id);
		}

		$entity = $this->customPreMapToEntity($result, $entity);

		if (empty($entity)) {
			$entity = $this->getNewEntity();
		}

		foreach ($this->fields as $prop => $fieldName) {
			$method = "set" . ucfirst($prop);
			if (isset($result[$fieldName]) && method_exists($entity, $method)) {
				$entity->$method($result[$fieldName]);
			}
		}

		$entity = $this->customMapToEntity($result, $entity);

		$this->addToCache($entity);

		return $entity;
	}

	protected function customPreMapToEntity($result, $entity) {
		return $entity;
	}

	/**
	 *  Inherited classes could implements specific mapping logic for a related entity
	 * e.g. delegate appropriate dependencies' mappings
	 * @param ArrayObject $data - data from DB
	 * @param Object $entity - entity object which must be populated
	 */
	protected function customMapToEntity($result, $entity) {
		return $entity;
	}

	/**
	 * Default map to array of entities
	 * @param ArrayObject|array $data
	 * @return multitype:Entity_* 
	 */
	protected function mapToEntities($data) {
		$entities = array();
		if (is_array($data) || $data instanceof \Traversable)
			foreach ($data as $row) {
				$entities[] = $this->mapToEntity($row);
			}
		return $entities;
	}

	/**
	 * Default map from entity to array
	 * Returns populated array of db fileds to pass into db adapter
	 *
	 * @param Object $entity
	 * @return array
	 */
	protected function mapFromEntity($entity) {
		$data = array();
		foreach ($this->getFields() as $prop => $fieldName) {
			$method = "get" . ucfirst($prop);
			if (method_exists($entity, $method)) {
				$data[$fieldName] = $entity->$method();
			}
		}

		return $data;
	}

	///////////////////////////////////////////  Caching ///////////////////////////////
	protected function addToCache($entity, $id = null) {
		if (self::$useCache) {
			if ($id == null) {
				$this->cache->offsetSet($entity->getId(), $entity);
			} else {
				$this->cache->offsetSet($id, $entity);
			}
		}
	}

	protected function isInCache($id) {
		if (self::$useCache) {
			return $this->cache->offsetExists($id);
		}

		return false;
	}

	protected function getFromCache($id) {
		return $this->cache->offsetGet($id);
	}
	///////////////////////////////////////////  Save ///////////////////////////////

	/**
	 * @param IdableInterface $entity
	 * @return Ambigous <number, string>|Ambigous <boolen, boolean>
	 */
	public function save($entity) {
		if ($entity->getId() == null) {
			return $this->insert($entity);
		} else {
			return $this->update($entity);
		}
	}

	/**
	 * @throws DomainEntityWrongPrimaryKey
	 * @param IdsetableInterface $entity
	 * @return IdsetableInterface
	 */
	public function insert($entity) {

		if ($entity->getId() != null) {
			throw new Exception\DomainEntityWrongPrimaryKey(
					"Entity " . get_class($entity)
							. " already has a primary key: " . $entity->getId());
		}

		$entity = $this->preInsertAction($entity);
		$values = $this->mapFromEntity($entity);
		unset($values[$this->primKey]);

		$insert = $this->getSql()->insert($this->table);
		$insert->values($values);

		$sql = $this->getSql()->getSqlStringForSqlObject($insert);
		$result = $this->db->query($sql, DbAdapter::QUERY_MODE_EXECUTE);
		$entity->setId($this->db->getDriver()->getLastGeneratedValue());

		$entity = $this->postInsertAction($entity);
		return $entity;
	}

	public function preInsertAction($entity) {
		return $entity;
	}

	public function postInsertAction($entity) {
		return $entity;
	}

	/**
	 * Update a record of relevant object in DB
	 * @param IdableInterface $entity
	 * @return IdableInterface
	 */
	public function update($entity) {

		if ($entity->getId() == null) {
			throw new Exception\DomainEntityWrongPrimaryKey(
					"Entity " . get_class($entity)
							. " does not have a primary key");
		}

		$entity = $this->preUpdateAction($entity);
		$values = $this->mapFromEntity($entity);

		$update = $this->getSql()->update($this->table);
		$update->where(array($this->primKey => $entity->getId()));
		unset($values[$this->primKey]);
		$update->set($values);

		$sql = $this->getSql()->getSqlStringForSqlObject($update);
		$result = $this->db->query($sql, DbAdapter::QUERY_MODE_EXECUTE);
		$result = $this->postUpdateAction($entity, $result);
		return $result;
	}

	protected function preUpdateAction($entity) {
		return $entity;
	}
	protected function postUpdateAction($entity, $updateResult) {
		return $updateResult;
	}

	///////////////////////////////////////////  Select ///////////////////////////////

	public function geById($id) {
		$id = (int) $id;
		if ($this->isInCache($id)) {
			return $this->getFromCache($id);
		}

		$select = $this->getSql()->select();
		$select->from($this->table);
		$select->where(array($this->primKey => $id));
		$select->limit(1);

		$sql = $this->getSql()->getSqlStringForSqlObject($select);
		$result = $this->db->query($sql, DbAdapter::QUERY_MODE_EXECUTE);

		if (($result instanceof \Zend\Db\ResultSet\ResultSet
				|| $result instanceof \Zend\Db\Adapter\Driver\ResultInterface)
				&& $result = $result->current()) {
			return $this->mapToEntity($result);
		}

		throw new Exception\DomainEntityNotFound(
				sprintf('Record in %s with id = %d does not exist',
						$this->table, $id));
	}

	//////////////////////// getters && setters //////////////////////

	public function getCache() {
		if ($this->cache == null) {
			$this->cache = new \ArrayObject();
		}
		return $this->cache;
	}

	public function setCache(\ArrayAccess $cache) {
		$this->cache = $cache;
	}

	public function getSql() {
		if ($this->sql == null) {
			$this->sql = new Sql($this->db);
		}
		return $this->sql;
	}

	public function setSql(Zend\Db\Sql\Sql $sql) {
		$this->sql = $sql;
	}

	public function setDbAdapter(DbAdapter $adapter) {
		$this->db = $adapter;
	}

	public function getFields() {
		return $this->fields;
	}

}
