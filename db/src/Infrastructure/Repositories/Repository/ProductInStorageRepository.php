<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Domain\Entity\ProductInStorage as DomainProductInStorage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Infrastructure\Repositories\Entity\ProductInStorage as ORMProductInStorage;
use App\Domain\Service\ProductInStorageRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;

/**
 * @extends ServiceEntityRepository<ORMProductInStorage>
 *
 * @method ProductInStorage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductInStorage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductInStorage[]    findAll()
 * @method ProductInStorage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductInStorageRepository extends ServiceEntityRepository implements ProductInStorageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMProductInStorage::class);
    }

    public function getNextId(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT max(s.id)
            FROM App\Infrastructure\Repositories\Entity\ProductInStorage s'
        );

        return $query->getResult()[0][1] + 1;
    }

    public function addProductInStorage(DomainProductInStorage $productInStorage): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($this->hydrateProductInStorage($productInStorage));
        $entityManager->flush();
    }

    public function updateProductInStorage(DomainProductInStorage $newProductInStorage): void
    {
        $entityManager = $this->getEntityManager();
        
        //$product = $entityManager->getRepository(Product::class)->find($id);
        $productInStorage = $this->find($newProductInStorage->getId());

        if (!$productInStorage) {
            throw $this->createNotFoundException(
                'No product found for id '.$newProductInStorage->getId()
            );
        }

        $productInStorage->setIdProduct($newProductInStorage->getIdProduct());
        $productInStorage->setIdStorage($newProductInStorage->getIdStorage());
        $productInStorage->setCount($newProductInStorage->getCount());
        $entityManager->flush();
    }

    private function hydrateProductInStorage(DomainProductInStorage $productInStorage): ORMProductInStorage
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ORMProductInStorage::class, [
            'id' => $productInStorage->getId(),
            'id_product' => $productInStorage->getIdProduct(),
            'id_storage' => $productInStorage->getIdStorage(),
            'count' => $productInStorage->getCount(),
        ]);
    }
}
