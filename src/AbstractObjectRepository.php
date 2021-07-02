<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository;

use function is_null;
use RuntimeException;
use Throwable;

/**
 * @template TYPE as object
 * @template ID as array<string, scalar>
 * @template CTORARGS as array<string, scalar|array|object|null>
 *
 * @template-implements ObjectRepository<TYPE, ID, CTORARGS>
 */
abstract class AbstractObjectRepository implements ObjectRepository
{
	/**
	 * @var array<string, TYPE>
	 */
	protected array $memory = [];

	public function __construct(array $_options)
	{
	}

	/**
	 * @param TYPE $object
	 */
	public function UpdateObject(
		object $object
	) : void {
		$hash = $this->RelaxedObjectHash($this->ObtainIdFromObject($object));

		$this->memory[$hash] = $object;
	}

	/**
	 * @param ID $id
	 */
	public function ForgetObject(array $id) : void
	{
		$hash = $this->RelaxedObjectHash($id);

		unset($this->memory[$hash]);
	}

	/**
	 * @param ID $id
	 *
	 * @return TYPE|null
	 */
	public function MaybeRecallObject(
		array $id
	) : ? object {
		$hash = $this->RelaxedObjectHash($id);

		/**
		 * @var TYPE|null
		 */
		return $this->memory[$hash] ?? null;
	}

	/**
	 * @param ID $id
	 * @param ID ...$ids
	 *
	 * @return list<TYPE>
	 */
	public function MaybeRecallManyObjects(array $id, array ...$ids) : array
	{
		array_unshift($ids, $id);

		return array_values(array_filter(array_map(
			[$this, 'MaybeRecallObject'],
			$ids
		)));
	}

	/**
	 * @param ID $id
	 *
	 * @return TYPE
	 */
	public function RecallObject(
		array $id,
		Throwable $not_found = null
	) : object {
		$maybe = $this->MaybeRecallObject($id);

		if (is_null($maybe)) {
			throw $not_found ?: new RuntimeException(
				'Object could not be found for the specified id!'
			);
		}

		return $maybe;
	}

	/**
	 * @param ID $id
	 */
	protected function RelaxedObjectHash(
		array $id
	) : string {
		return hash('sha512', json_encode($id), true);
	}
}
