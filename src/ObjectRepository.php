<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository;

use Throwable;

/**
 * @template TYPE as object
 * @template ID as array<string, scalar>
 * @template CTORARGS as array<string, scalar|array|object|null>
 */
interface ObjectRepository
{
	/**
	 * @param CTORARGS $_options
	 */
	public function __construct(array $_options);

	/**
	 * @param TYPE $object
	 */
	public function UpdateObject(
		object $object
	) : void;

	/**
	 * @param ID $id
	 */
	public function ForgetObject(
		array $id
	) : void;

	/**
	 * @param ID $id
	 */
	public function RemoveObject(
		array $id
	) : void;

	/**
	 * @param ID $id
	 */
	public function RecallObject(
		array $id,
		Throwable $not_found = null
	) : object;

	/**
	 * @param ID $id
	 */
	public function MaybeRecallObject(
		array $id
	) : ? object;

	/**
	 * Possible returns many objects, or none.
	 *
	 * Return order is not guaranteed to correspond to argument order
	 *
	 * @param ID $id
	 * @param ID ...$ids
	 *
	 * @return list<TYPE>
	 */
	public function MaybeRecallManyObjects(array $id, array ...$ids) : array;

	/**
	 * @param TYPE $object
	 *
	 * @return ID
	 */
	public function ObtainIdFromObject(object $object) : array;
}
