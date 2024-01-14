<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories\Repository;

use App\Domain\Entity\Product as DomainProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Infrastructure\Repositories\Entity\Product as ORMProduct;
use App\Domain\Service\ProductRepositoryInterface;
use App\Infrastructure\Hydrator\Hydrator;
/**
 * @extends ServiceEntityRepository<ORMProduct>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMProduct::class);
    }

    public function getNextId(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT max(s.id)
            FROM App\Infrastructure\Repositories\Entity\Product s'
        );

        return $query->getResult()[0][1] + 1;
    }

    public function addProduct(DomainProduct $product): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($this->hydrateProduct($product));
        $entityManager->flush();
    }

    public function updateProduct(DomainProduct $newProduct): void
    {
        $entityManager = $this->getEntityManager();
        
        //$product = $entityManager->getRepository(Product::class)->find($id);
        $product = $this->find($newProduct->getId());

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$newProduct->getId()
            );
        }

        $product->setName($newProduct->getName());
        $product->setDescryption($newProduct->getDescryption());
        $product->setCategory($newProduct->getCategory());
        $product->setCost($newProduct->getCost());
        $product->setPhoto($newProduct->getPhoto());
        $entityManager->flush();
    }

    private function hydrateProduct(DomainProduct $product): ORMProduct
    {
        $hydrator = new Hydrator();
        return $hydrator->hydrate(ORMProduct::class, [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'descryption' => $product->getDescryption(),
            'category_id' => $product->getCategory(),
            'cost' => $product->getCost(),
            'photo' => $product->getPhoto(),
        ]);
    }
}
