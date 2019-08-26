<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftTypedObject;

/**
* @template T1 as DaftTypedObjectForRepository
* @template T2 as array<string, scalar>
*
* @template-extends DaftTypedObjectRepository<T1, T2>
*/
interface AppendableTypedObjectRepository extends DaftTypedObjectRepository
{
	/**
	* @param T1 $object
	*
	* @return T1
	*/
	public function AppendTypedObject(
		DaftTypedObjectForRepository $object
	) : DaftTypedObjectForRepository;
}
