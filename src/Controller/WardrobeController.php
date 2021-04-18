<?PHP

// src/Controller/WardrobeController
namespace App\Controller;

use App\Entity\Wardrobe;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;


class WardrobeController extends AbstractController
{
   
    /**
     * @Route("/api/wardrobe/get/", name="wardrobeController_getWardrobe")
     */
    public function getWardrobe(){
        $wardrobe = $this->getDoctrine()->getRepository(Wardrobe::class)->findAll();
        return $this->json($wardrobe, 200, [], ["groups"=>["show_wardrobe"]]);
         
    }
    /**
     * @Route("/api/wardrobe/create", name="wardrobeController_createWardrobe")
     */
    public function createWardrobe(){
        $entityManager = $this->getDoctrine()->getManager();

        $wardrobe = new Wardrobe();
        $entityManager->persist($wardrobe);
        $entityManager->flush();

        return $this->redirect("/");
         
    }

    /**
     * @Route("/api/wardrobe/remove/", name="wardrobeController_removeWardrobe")
     */
    public function removeWardrobe(int $id){
        $entityManager = $this->getDoctrine()->getManager();
        $wardrobe = $this->getDoctrine()->getRepository(Wardrobe::class)->find($id);

        $entityManager->remove($wardrobe);
        $entityManager->flush();

        return $this->redirect("/");
         
    }
    
    
}
?>