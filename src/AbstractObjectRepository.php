<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftRelaxedObjectRepository;

use RuntimeException;
use Throwable;

/**
 * @template T1 as object
 * @template T2 as array<string, scalar>
 *
 * @template-implements ObjectRepository<T1, T2>
 */
abstract class AbstractObjectRepository implements ObjectRepository
{
	/**
	 * @readonly
	 *
	 * @var class-string<T1>
	 */
	public string $type;

	/**
	 * @var array<string, T1>
	 */
	protected array $memory = [];

	/**
	 * @param array{type:class-string<T1>} $options
	 */
	public function __construct(array $options)
	{
		$this->type = $options['type'];
	}

	/**
	 * @param T1 $object
	 */
	public function UpdateObject(
		object $object
	) : void {
		$hash = static::RelaxedObjectHash($this->ObtainIdFromObject($object));

		$this->memory[$hash] = $object;
	}

	/**
	 * @param T2 $id
	 */
	public function ForgetObject(array $id) : void
	{
		$hash = static::RelaxedObjectHash($id);

		unset($this->memory[$hash]);
	}

	/**
	 * @param T2 $id
	 *
	 * @return T1|null
	 */
	public function MaybeRecallObject(
		array $id
	) : ? object {
		$hash = static::RelaxedObjectHash($id);

		/**
		 * @var T1|null
		 */
		return $this->memory[$hash] ?? null;
	}

	/**
	 * @param T2 $id
	 *
	 * @return T1
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
