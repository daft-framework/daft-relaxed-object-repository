<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftTypedObject\Fixtures;

use RuntimeException;
use SignpostMarv\DaftTypedObject\AbstractDaftTypedObjectRepository;
use SignpostMarv\DaftTypedObject\AppendableTypedObjectRepository;
use SignpostMarv\DaftTypedObject\DaftTypedObjectForRepository;
use Throwable;

/**
* @template T1 as MutableForRepository
* @template T2 as array{id:int}
*
* @template-extends AbstractDaftTypedObjectRepository<T1, T2>
*
* @template-implements AppendableTypedObjectRepository<T1, T2>
*/
class DaftTypedObjectMemoryRepository extends AbstractDaftTypedObjectRepository implements AppendableTypedObjectRepository
{
	/**
	* @var array<string, array{id:int, name:string}>
	*/
	protected $data = [];

	/**
	* @var array<string, T1>
	*/
	protected $memory = [];

	/**
	* @param T1 $object
	*
	* @return T1
	*/
	public function AppendTypedObject(
		DaftTypedObjectForRepository $object
	) : DaftTypedObjectForRepository {
		$new_id = max(0, count($this->data)) + 1;

		$data = [
			'id' => $new_id,
			'name' => $object->name,
		];

		$hash = static::DaftTypedObjectHash(['id' => $new_id]);

		$this->data[$hash] = $data;

		$type = $this->type;

		/**
		* @var T1
		*/
		$object = new $type($data);

		$this->memory[$hash] = $object;

		/**
		* @var T1
		*/
		return $object;
	}

	public function UpdateTypedObject(
		DaftTypedObjectForRepository $object
	) : void {
		/**
		* @var T2
		*/
		$id = $object->ObtainId();

		$hash = static::DaftTypedObjectHash($id);

		parent::UpdateTypedObject($object);

		$this->data[$hash] = $object->__toArray();
	}

	public function RemoveTypedObject(array $id) : void
	{
		/**
		* @var T2
		*/
		$id = $id;

		$hash = static::DaftTypedObjectHash($id);

		$this->ForgetTypedObject($id);
		unset($this->data[$hash]);
	}

	/**
	* @return T1|null
	*/
	public function MaybeRecallTypedObject(
		array $id
	) : ? DaftTypedObjectForRepository {
		/**
		* @var T2
		*/
		$id = $id;

		/**
		 * @var T1|null
		 */
		$maybe = parent::MaybeRecallTypedObject($id);

		if (is_null($maybe)) {
			$hash = static::DaftTypedObjectHash($id);

			$row = $this->data[$hash] ?? null;

			$type = $this->type;

			if (is_array($row)) {
				/**
				* @var T1
				*/
				$object = $type::__fromArray($row);

				$this->UpdateTypedObject($object);

				return $object;
			}

			return null;
		}

		return $maybe;
	}
}
