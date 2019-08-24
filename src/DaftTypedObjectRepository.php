<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftTypedObject;

use Throwable;

/**
* @template T1 as DaftTypedObjectForRepository
* @template T2 as array<string, scalar>
*/
interface DaftTypedObjectRepository
{
	/**
	* @param class-string<T1> $type
	*/
	public function __construct(string $type);

	/**
	* @param T1 $object
	*/
	public function UpdateTypedObject(
		DaftTypedObjectForRepository $object
	) : void;

	/**
	* @param T2 $id
	*/
	public function ForgetTypedObject(
		array $id
	) : void;

	/**
	* @param T2 $id
	*/
	public function RemoveTypedObject(
		array $id
	) : void;

	/**
	* @param T2 $id
	*/
	public function RecallTypedObject(
		array $id,
		Throwable $not_found = null
	) : DaftTypedObjectForRepository;

	/**
	* @param T2 $id
	*/
	public function MaybeRecallTypedObject(
		array $id
	) : ? DaftTypedObjectForRepository;
}
