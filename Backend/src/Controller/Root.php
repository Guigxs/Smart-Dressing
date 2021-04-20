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
        $wardrobe = $this->getDoctrine()->getRepository(Wardrobe::class)->findAll();
        $location = $this->getDoctrine()->getRepository(Location::class)->findAll();

        $content = $this->client->request("GET", "https://restcountries.eu/rest/v2/all");
        $locationList = $content->toArray();

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

            $response = $this->forward('App\Controller\CategoryController::createCategory', [
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

            $response = $this->forward('App\Controller\ClothController::createCloth', [
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

}

?>