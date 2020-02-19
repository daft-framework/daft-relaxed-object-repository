<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftRelaxedObjectRepository;

use Throwable;

/**
 * @template T1 as object
 * @template T2 as array<string, scalar>
 *
 * @property-read class-string<T1> $type
 */
interface ObjectRepository
{
	/**
	 * @param array{type:class-string<T1>} $options
	 */
	public function __construct(array $options);

	/**
	 * @param T1 $object
	 */
	public function UpdateObject(
		object $object
	) : void;

	/**
	 * @param T2 $id
	 */
	public function ForgetObject(
		array $id
	) : void;

	/**
	 * @param T2 $id
	 */
	public function RemoveObject(
		array $id
	) : void;

	/**
	 * @param T2 $id
	 */
	public function RecallObject(
		array $id,
		Throwable $not_found = null
	) : object;

	/**
	 * @param T2 $id
	 */
	public function MaybeRecallObject(
		array $id
	) : ? object;

	/**
	 * @param T1 $object
	 *
	 * @return T2
	 */
	public function ObtainIdFromObject(object $object) : array;
}
