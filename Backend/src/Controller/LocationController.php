<?PHP

namespace App\Controller;

use App\Entity\Location;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;


class LocationController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getLocation(){
        $locations = $this->getDoctrine()->getRepository(Location::class)->findAll();
        return $this->json($locations, 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], ["groups"=>["show_location"]]);
    }

    public function getAvailableLocation(){
        $content = $this->client->request("GET", "https://restcountries.eu/rest/v2/all");
        $locationList = $content->toArray();
        return $this->json($locationList, 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"]);
    }

    public function updateLocation(string $country, string $city, Request $request){
        if ($request->isMethod('OPTIONS')) {
            return $this->json([], 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*", "Access-Control-Allow-Methods" => "*"]);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $location = new Location();
        $location->setCountry($country);
        $location->setCity($city);

        $entityManager->persist($location);
        $entityManager->flush();

        return $this->json($location, 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], ["groups"=>["show_location"]]);
    }

    public function removeLocation(Request $request){
        if ($request->isMethod('OPTIONS')) {
            return $this->json([], 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*", "Access-Control-Allow-Methods" => "*"]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $locations = $this->getDoctrine()->getRepository(Location::class)->findAll();

        foreach ($locations as $entity) {
            $entityManager->remove($entity);
        }

        $entityManager->flush();

        return $this->json($locations, 202, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"], ["groups"=>["show_location"]]);
    }

    public function getWeather(){
        $locations = $this->getDoctrine()->getRepository(Location::class)->findAll();
        $response = $this->client->request(
            'GET',
            "http://api.openweathermap.org/data/2.5/weather?q={$locations[0]->getCountry()}, {$locations[0]->getCity()}&appid={$_SERVER['API_KEY']}&units=metric"
        );
        $content = $response->toArray();
        return $this->json($content, 200, ["Access-Control-Allow-Origin" => "*", "Access-Control-Allow-Headers" => "*"]);
    }
}
?>