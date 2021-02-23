<?PHP

// src/Controller/Root
namespace App\Controller;

use App\Entity\Cloth;
use App\Entity\Category;
use App\Entity\Wardrobe;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
            // $content = ["name"=>"Waterlooooo", "main"=>["feels_like"=>200]];
            $clothers = $this->getDoctrine()->getRepository(Cloth::class)->findAll();
            
            return $this->render("dashboard.twig", [
                "wardrobe" => $wardrobe[0],
                "location" => $location[0],
                "wheather" => $content,
                "clothers" => $clothers
            ]);
        }
    }

    public function new(Request $request): Response{
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        
        $category = new Category();
        $cloth = new Cloth();


        $categoryForm = $this->createFormBuilder($category)
                ->add("name")
                ->add("temperature", ChoiceType::class, [
                    "choices" => [
                        "- 10" => -10,
                        "- 5" => -5,
                        "0" => 0,
                        "5" => 5,
                        "10" => 10,
                        "20" => 20,
                        "30" => 30
                    ]
                ])
                ->add("weather", ChoiceType::class, [
                    "choices" => [
                        "Sunny" => "sunny",
                        "Rainy" => "rainy",
                        "Foggy" => "foggy",
                        "Cloudy" => "cloudy",
                        "Thunderstorm" => "thunderstorm",
                        "Snowy" => "snowy"
                    ]
                ])
                ->add("rainLevel", ChoiceType::class, [
                    "choices" => [
                        "None" => "none",
                        "Drizzle" => "drizzle",
                        "Medium" => "medium",
                        "Heavy" => "heavy"
                    ]
                ])
                ->add("submit", SubmitType::class, [
                    "label"=>"Envoyer"
                ])
                ->getForm();

                $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            $response = $this->forward('App\Controller\Root::createCategory', [
                'category'  => $categoryForm->getData(),
            ]);

            return $response;
        }

        $clothForm = $this->createFormBuilder($cloth)
                ->add("name")
                ->add("category", EntityType::class, [
                    "multiple" => true,
                    "class" => Category::class,
                    'choice_label' => "name",
                ])
                ->add("color", null, [
                    "required" => true,
                ])
                ->add("fabric", null, [
                    "required" => true,
                ])
                ->add("quantity", ChoiceType::class, [
                    "choices"=> [
                        "1" => 1,
                        "2" => 2,
                        "3" => 3,
                        "4" => 4,
                        "5" => 5,
                    ]
                ])
                ->add("submit", SubmitType::class, [
                    "label"=>"Envoyer"
                ])
                ->getForm();

        $clothForm->handleRequest($request);

        if ($clothForm->isSubmitted() && $clothForm->isValid()) {

            $response = $this->forward('App\Controller\Root::createCloth', [
                'cloth'  => $clothForm->getData(),
            ]);

            return $response;
        }


        return $this->render("new.twig", [
            "categories"=>$categories,
            "newCategoryForm" => $categoryForm->createView(),
            "newClothForm"=>$clothForm->createView()
        ]);
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

    /**
     * @Route("/create/category", name="root_createCategory")
     */
    public function createCategory(Category $category){
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->redirect("/");
    }
    
    /**
     * @Route("/create/cloth", name="root_createCloth")
     */
    public function createCloth(Cloth $cloth){
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($cloth);
        $entityManager->flush();

        return $this->redirect("/");
    }

    /**
     * @Route("/remove/cloth", name="root_removeCloth")
     */
    public function removeCloth(string $id){
        $entityManager = $this->getDoctrine()->getManager();
        $cloth = $this->getDoctrine()->getRepository(Cloth::class)->find($id);

        $entityManager->remove($cloth);
        $entityManager->flush();

        return $this->redirect("/");
    }

    /**
     * @Route("/search", name="root_search")
     */
    public function search(string $searchField){
        
        if (empty($category)){
            $clothers = $this->getDoctrine()->getRepository(Cloth::class)->findBy(["name" => $searchField]);
            dump($clothers);
            return $this->render("searchResult.twig", [
                "items" => $clothers
            ]);
        }
    }
}

?>