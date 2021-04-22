<?PHP

// src/Controller/CategoryController
namespace App\Controller;

use App\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryController extends AbstractController
{
    public function getCategories(){
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        
        return $this->json($categories, 200, ["Access-Control-Allow-Origin" => "*"], ["groups"=>["show_category"]]);
    }

    public function createCategory(Request $request, SerializerInterface $serializer, ValidatorInterface $validator){
        if ($request->isMethod('OPTIONS')) {
            return $this->json([], 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*", "Access-Control-Allow-Methods" => "*"]);
        }

        try{
            $category = $serializer->deserialize($request->getContent(), Category::class, "json");
            
            $errors = $validator->validate($category);
            if (count($errors) > 0){
                return $this->json([$errors], 400, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"]);
            }

        }catch(NotEncodableValueException $e) {
            return $this->json([
                "error"=>$e->getMessage()
            ], 400, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"]);
        }
        
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json($category, 201, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], ["groups"=>["show_cloth"]]);
    }

    public function removeCategory(string $id, Request $request){
        if ($request->isMethod('OPTIONS')) {
            return $this->json([], 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*", "Access-Control-Allow-Methods" => "*"]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->json($category, 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], ["groups"=>["show_category"]]);
    }   
}
?>