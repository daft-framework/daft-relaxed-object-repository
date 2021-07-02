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
 * @template OBJECT as object
 * @template ID as array<string, scalar>
 * @template CTORARGS as array<string, scalar|array|object|null>
 *
 * @template-implements ObjectRepository<OBJECT, ID, CTORARGS>
 */
abstract class AbstractObjectRepository implements ObjectRepository
{
	/**
	 * @var array<string, OBJECT>
	 */
	protected array $memory = [];

	public function __construct(array $_options)
	{
	}

	/**
	 * @param OBJECT $object
	 */
	public function UpdateObject(
		object $object
	) : void {
		$hash = static::RelaxedObjectHash($this->ObtainIdFromObject($object));

		$this->memory[$hash] = $object;
	}

	/**
	 * @param ID $id
	 */
	public function ForgetObject(array $id) : void
	{
		$hash = static::RelaxedObjectHash($id);

		unset($this->memory[$hash]);
	}

	/**
	 * @param ID $id
	 *
	 * @return OBJECT|null
	 */
	public function MaybeRecallObject(
		array $id
	) : ? object {
		$hash = static::RelaxedObjectHash($id);

		/**
		 * @var OBJECT|null
		 */
		return $this->memory[$hash] ?? null;
	}

	/**
	 * @param ID $id
	 *
	 * @return OBJECT
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
	 * @param array<string, scalar> $id
	 */
	protected static function RelaxedObjectHash(
		array $id
	) : string {
		return hash('sha512', json_encode($id), true);
	}
}
