<?PHP

// src/Controller/CategoryController
namespace App\Controller;

use App\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
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