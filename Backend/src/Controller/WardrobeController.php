<?PHP

// src/Controller/WardrobeController
namespace App\Controller;

use App\Entity\Wardrobe;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class WardrobeController extends AbstractController
{
    public function getWardrobe(){
        $wardrobe = $this->getDoctrine()->getRepository(Wardrobe::class)->findAll();
        return $this->json($wardrobe, 200, [], ["groups"=>["show_wardrobe"]]);
    }

    public function createWardrobe(){
        $entityManager = $this->getDoctrine()->getManager();

        $wardrobe = new Wardrobe();
        $entityManager->persist($wardrobe);
        $entityManager->flush();

        return $this->json($wardrobe, 201, [], ["groups"=>["show_wardrobe"]]);
    }

    public function removeWardrobe(int $id){
        $entityManager = $this->getDoctrine()->getManager();
        $wardrobe = $this->getDoctrine()->getRepository(Wardrobe::class)->find($id);
        
        if ($wardrobe == null){
            return $this->json([
                "error"=>"Wardrobe not found"
                ], 400, [], []);
        }

        $entityManager->remove($wardrobe);
        $entityManager->flush();
        
        return $this->json($wardrobe, 202, [], ["groups"=>["show_wardrobe"]]);
    }
}
?>