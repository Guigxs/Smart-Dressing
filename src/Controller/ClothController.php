<?PHP

// src/Controller/ClothController
namespace App\Controller;

use App\Entity\Cloth;
use App\Entity\Category;
use App\Entity\Wardrobe;
use App\Entity\Location;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;


class ClothController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    
    
    public function getCloth(){
        $wardrobe = $this->getDoctrine()->getRepository(Wardrobe::class)->findAll();
        $location = $this->getDoctrine()->getRepository(Location::class)->findAll();

        if (!empty($wardrobe) && !empty($location)){

            $response = $this->client->request(
                'GET',
                "http://api.openweathermap.org/data/2.5/weather?q={$location[0]->getCity()},{$location[0]->getCountry()}&appid={$_SERVER['API_KEY']}&units=metric"
            );

            $content = $response->toArray();
            // $content = ["name"=>"Waterlooooo", "main"=>["feels_like"=>-10], "weather"=>[["id"=>800]]];
            
            $temperature = $content["main"]["feels_like"];
            $weather = intval($content["weather"][0]["id"]);

            if (802 <= $weather && $weather < 900){
                // Cloudy
                $weatherQuery = "cloudy";
                $rainQuery = "none";
            }
            else if (800 == $weather){
                //sunny
                $weatherQuery = "sunny";
                $rainQuery = "none";
            }
            else if (701 == $weather || 721 == $weather || 741 == $weather){
                //foggy
                $weatherQuery = "foggy";
                $rainQuery = "none";
            }
            else if (600 <= $weather && $weather < 700){
                //snowy
                $weatherQuery = "snowy";
                if ($weather == 600 ){
                    $rainQuery = "drizzle";
                }
                if ($weather == 601 || $weather == 612 ||  $weather == 615){
                    $rainQuery = "medium";
                }
                else{
                    $rainQuery = "heavy";
                }
            }
            else if (200 <= $weather && $weather < 300){
                //thunderstorm
                $weatherQuery = "thunderstorm";
                $rainQuery = "none";
            }
            else if (300 <= $weather && $weather < 600){
                //rainy
                $weatherQuery = "rainy";
                if ((300 <= $weather && $weather <= 302) || $weather == 500){
                    // Drizzle
                    $rainQuery = "drizzle";
                }
                else if ((302 < $weather && $weather <= 311) || (501 == $weather)){
                    // medium rain
                    $rainQuery = "medium";
                }
                else if ((311 < $weather && $weather < 400) || (502 <= $weather && $weather < 600)) {
                    //heavy rain
                    $rainQuery = "heavy";
                }
            }
            else{
                //undefined
                $weatherQuery = "";
                $rainQuery = "none";
            }

            if ($temperature <= -10){
                // -10
                $temperatureQuery = -10;
            }
            else if ($temperature > -10 && $temperature <= -5){
                // -5
                $temperatureQuery = -5;
            }
            else if ($temperature > -5 && $temperature <= 0){
                // 0
                $temperatureQuery = 0;
            }
            else if ($temperature > 0 && $temperature <= 5){
                // 5
                $temperatureQuery = 5;
            }
            else if ($temperature > 5 && $temperature <= 10){
                // 10
                $temperatureQuery = 10;
            }
            else if ($temperature > 10 && $temperature <= 20){
                // 20
                $temperatureQuery = 20;
            }
            else if ($temperature > 20){
                // 30
                $temperatureQuery = 30;
            }

            $categories = $this->getDoctrine()->getRepository(Category::class)->findBestCategory($temperatureQuery, $weatherQuery, $rainQuery);
            
            if (empty($categories)){
                return $this->render("empty.twig");
            }

            $clothers = $categories[0]->getCloths();
            
            return $this->render("dashboard.twig", [
                "wardrobe" => $wardrobe[0],
                "location" => $location[0],
                "wheather" => $content,
                "clothers" => $clothers
            ]);
        }
    }

    /**
     * @Route("/create/cloth", name="clothController_createCloth")
     */
    public function createCloth(Cloth $cloth){
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($cloth);
        $entityManager->flush();

        return $this->redirect("/");
    }

    /**
     * @Route("/remove/cloth", name="clothController_removeCloth")
     */
    public function removeCloth(string $id){
        $entityManager = $this->getDoctrine()->getManager();
        $cloth = $this->getDoctrine()->getRepository(Cloth::class)->find($id);

        $entityManager->remove($cloth);
        $entityManager->flush();

        return $this->redirect("/");
    }

    /**
     * @Route("/search", name="clothController_search")
     */
    public function search(string $searchField){
        
        if (empty($category)){
            $clothers = $this->getDoctrine()->getRepository(Cloth::class)->createQueryBuilder("c")
                ->where("c.name LIKE :name")
                ->setParameter('name', $searchField.'%')
                ->getQuery()
                ->getResult();

            return $this->render("searchResult.twig", [
                "items" => $clothers
            ]);
        }
    }

    /**
     * @Route("/getClothByCategory", name="clothController_getClothByCategory")
     */
    public function getClothByCategory(string $id){
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        dump($category);
        $clothers = $category->getCloths();
        dump($clothers);
        return $this->render("searchResult.twig", [
            "items" => $clothers
        ]);
    }
}
?>