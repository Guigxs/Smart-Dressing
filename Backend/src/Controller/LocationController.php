<?PHP

namespace App\Controller;

use App\Entity\Location;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class LocationController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getLocation(){
        $locations = $this->getDoctrine()->getRepository(Location::class)->findAll();
        return $this->json($locations, 200, [], ["groups"=>["show_location"]]);
    }

    public function getAvailableLocation(){
        $content = $this->client->request("GET", "https://restcountries.eu/rest/v2/all");
        $locationList = $content->toArray();
        return $this->json($locationList, 200);
    }

    public function updateLocation(string $country, string $city){
        $entityManager = $this->getDoctrine()->getManager();

        $location = new Location();
        $location->setCountry($country);
        $location->setCity($city);

        $entityManager->persist($location);
        $entityManager->flush();

        return $this->json($location, 200, [], ["groups"=>["show_location"]]);
    }

    public function removeLocation(){
        $entityManager = $this->getDoctrine()->getManager();
        $locations = $this->getDoctrine()->getRepository(Location::class)->findAll();

        foreach ($locations as $entity) {
            $entityManager->remove($entity);
        }

        $entityManager->flush();

        return $this->json($locations, 202, [], ["groups"=>["show_location"]]);
    }
}
?>