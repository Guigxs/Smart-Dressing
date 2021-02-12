<?PHP

// src/Controller/Root
namespace App\Controller;

use App\Entity\Wardrobe;
use App\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Root extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/", name="root_main")
     */
    public function main()
    {
        // echo $_SERVER['API_KEY'];
        $wardrobe = $this->getDoctrine()->getRepository(Wardrobe::class)->findAll();
        $location = $this->getDoctrine()->getRepository(Location::class)->findAll();

        $locationList = [["city"=>"Waterloo", "country"=>"BE"], ["city"=>"Brussels", "country"=>"BE"], ["city"=>"Liege", "country"=>"BE"]];

        if (empty($location)){
            return $this->render("location.twig", [
                "locationList" => $locationList
            ]);
        }
        else if (empty($wardrobe)){
            return $this->render("index.twig");
        }
        else{
            $response = $this->client->request(
                'GET',
                "http://api.openweathermap.org/data/2.5/weather?q={$location[0]->getCity()},{$location[0]->getCountry()}&appid={$_SERVER['API_KEY']}&units=metric"
            );
            $content = $response->toArray();
            return $this->render("dashboard.twig", [
                "wardrobe" => $wardrobe[0],
                "location" => $location[0],
                "wheather" => $content
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

    /**
     * @Route("/remove/wardrobe", name="root_removeWardrobe")
     */
    public function removeWardrobe(int $id){
        $entityManager = $this->getDoctrine()->getManager();
        $wardrobe = $this->getDoctrine()->getRepository(Wardrobe::class)->find($id);

        $entityManager->remove($wardrobe);
        $entityManager->flush();

        return $this->redirect("/");
         
    }

    /**
     * @Route("/update/location", name="root_updateLocation")
     */
    public function updateLocation(string $country, string $city){
        $entityManager = $this->getDoctrine()->getManager();

        $location = new Location();
        $location->setCountry($country);
        $location->setCity($city);

        $entityManager->persist($location);
        $entityManager->flush();

        return $this->redirect("/");
    }

    /**
     * @Route("/remove/location", name="root_removeLocation")
     */
    public function removeLocation(){
        $entityManager = $this->getDoctrine()->getManager();
        $locations = $this->getDoctrine()->getRepository(Location::class)->findAll();

        foreach ($locations as $entity) {
            $entityManager->remove($entity);
        }

        $entityManager->flush();

        return $this->redirect("/");
    }
    
}

?>