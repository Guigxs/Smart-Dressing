<?PHP

// src/Controller/Root
namespace App\Controller;

use App\Entity\Wardrobe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Root extends AbstractController
{
    /**
     * @Route("/", name="root_main")
     */
    public function main()
    {

        $wardrobe = $this->getDoctrine()->getRepository(Wardrobe::class)->findAll();
        // var_dump($wardrobe->id)
        if (empty($wardrobe)){
            return $this->render("index.twig");
        }
        else{
            return $this->render("dashboard.twig", [
                "wardrobe" => $wardrobe[0]
            ]);
        }
    }

    /**
     * @Route("/create/wardrobe", name="root_createWardrobe")
     */
    public function createWardrobe(){
        $entityManager = $this->getDoctrine()->getManager();

        $wardrobe = new Wardrobe();
        $entityManager->persist($wardrobe);
        $entityManager->flush();

        return $this->redirect("/");
         
    }

    public function removeWardrobe(int $id){
        $entityManager = $this->getDoctrine()->getManager();
        $wardrobe = $this->getDoctrine()->getRepository(Wardrobe::class)->find($id);

        $entityManager->remove($wardrobe);
        $entityManager->flush();

        return $this->redirect("/");
         
    }
    
}

?>