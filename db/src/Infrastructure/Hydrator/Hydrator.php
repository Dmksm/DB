<?php
declare(strict_types=1);
namespace App\Infrastructure\Hydrator;

class Hydrator
{
   private array $reflectionMap = [];

    /**
     * @throws \ReflectionException
     */
    public function hydrate(string $class, array $data): mixed
   {
       $reflection = $this->getReflection($class);

       $result = $reflection->newInstanceWithoutConstructor();
       foreach ($data as $name => $value)
       {
           $prop = $reflection->getProperty($name);
           if (!$prop->isPublic())
           {
               $prop->setAccessible(true);
           }
           $prop->setValue($result, $value);
       }
       return $result;
   }

    /**
     * @throws \ReflectionException
     */
    private function getReflection(string $class): \ReflectionClass
   {
       if (!array_key_exists($class, $this->reflectionMap))
       {
           $this->reflectionMap[$class] = new \ReflectionClass($class);
       }
       return $this->reflectionMap[$class];
   }
}
