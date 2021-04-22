<?PHP

// src/Controller/WardrobeController
namespace App\Controller;

use App\Entity\Wardrobe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class WardrobeController extends AbstractController
{
    
    public function getWardrobe(){
        $wardrobe = $this->getDoctrine()->getRepository(Wardrobe::class)->findAll();
        return $this->json($wardrobe, 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], ["groups"=>["show_wardrobe"]]);
    }

    public function createWardrobe(Request $request){
        if ($request->isMethod('OPTIONS')) {
            return $this->json([], 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*", "Access-Control-Allow-Methods" => "*"]);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $wardrobe = new Wardrobe();
        $entityManager->persist($wardrobe);
        $entityManager->flush();

        return $this->json($wardrobe, 201, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], ["groups"=>["show_wardrobe"]]);
    }

    public function removeWardrobe(int $id, Request $request){
        if ($request->isMethod('OPTIONS')) {
            return $this->json([], 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*", "Access-Control-Allow-Methods" => "*"]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $wardrobe = $this->getDoctrine()->getRepository(Wardrobe::class)->find($id);
        
        if ($wardrobe == null){
            return $this->json([
                "error"=>"Wardrobe not found"
                ], 400, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], []);
        }

        $entityManager->remove($wardrobe);
        $entityManager->flush();
        
        return $this->json($wardrobe, 202, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], ["groups"=>["show_wardrobe"]]);
    }
}
?>