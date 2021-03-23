<?PHP

// src/Controller/CategoryController
namespace App\Controller;

use App\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    /**
     * @Route("/api/category/all", name="categoryController_getCategories")
     */
    public function getCategories(SerializerInterface $serializer){
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->json($categories, 200, [], ["groups"=>["show_category"]]);
    }
    /**
     * @Route("/create/category", name="categoryController_createCategory")
     */
    public function createCategory(Category $category){
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->redirect("/");
    }

    /**
     * @Route("/remove/category", name="categoryController_removeCategory")
     */
    public function removeCategory(string $id){
        $entityManager = $this->getDoctrine()->getManager();
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirect("/");
    }   
}
?>