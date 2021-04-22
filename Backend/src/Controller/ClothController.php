<?PHP

// src/Controller/ClothController
namespace App\Controller;

use App\Entity\Cloth;
use App\Entity\Category;
use App\Entity\Wardrobe;
use App\Entity\Location;
use ErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClothController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getAllCloth($catId){
        if($catId == null){
            $clothers = $this->getDoctrine()->getRepository(Cloth::class)->findAll();
        }
        else{
            $category = $this->getDoctrine()->getRepository(Category::class)->find($catId);
            $clothers = $category->getCloths();
        }
        return $this->json($clothers, 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], ["groups"=>["show_cloth"]]);
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
                return $this->json(["error"=>"There is no category for this weather"], 300, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], []);
            }

            $clothers = $categories[0]->getCloths();

            return $this->json($clothers, 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], ["groups"=>["show_cloth"]]);
        }
    }

    public function createCloth(Request $request, DenormalizerInterface $denormalizer, ValidatorInterface $validator){
        if ($request->isMethod('OPTIONS')) {
            return $this->json([], 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*", "Access-Control-Allow-Methods" => "*"]);
        }
        
        try{
            $data = json_decode($request->getContent(), true);
            $categories = $data["categories"];
            $cloth = $denormalizer->denormalize($data, Cloth::class);

            $errors = $validator->validate($cloth);
            if (count($errors) > 0){
                return $this->json([$errors], 400, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*", "Access-Control-Allow-Methods" => "*"]);
            }

        }catch(ErrorException $e) {
            return $this->json([
                "error"=>"Syntax error"
            ], 400, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*",]);
        }
        
        
        foreach ($categories as $cat){
            $category = $this->getDoctrine()->getRepository(Category::class)->find($cat);
            if ($category){
                $cloth->addCategory($category);
            }
            else{
                return $this->json([
                    "error"=>"One on more category does not exist"
                ], 400, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"]);
            }
        }

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($cloth);
        $entityManager->flush();

        return $this->json($cloth, 201, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], ["groups"=>["show_cloth"]]);
    }

    public function removeCloth(string $id, Request $request){
        if ($request->isMethod('OPTIONS')) {
            return $this->json([], 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*", "Access-Control-Allow-Methods" => "*"]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $cloth = $this->getDoctrine()->getRepository(Cloth::class)->find($id);

        $entityManager->remove($cloth);
        $entityManager->flush();

        return $this->json($cloth, 202, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], ["groups"=>["show_cloth"]]);
    }

    public function searchCloth(string $searchField=""){ 
        if (empty($category)){
            $clothers = $this->getDoctrine()->getRepository(Cloth::class)->createQueryBuilder("c")
                ->where("c.name LIKE :name")
                ->setParameter('name', $searchField.'%')
                ->getQuery()
                ->getResult();

            return $this->json($clothers, 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], ["groups"=>["show_cloth"]]);
        }
    }
}
?>