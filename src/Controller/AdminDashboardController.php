<?php

namespace App\Controller;

use DrewM\MailChimp\MailChimp;
use App\Service\GoogleAnalyticsService;
use App\Repository\PublicationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard_index")
     */
    public function index(PublicationRepository $repo)
    {   

        $nb = 3;
        $nbActu = $repo->countByCategory('actualite');
        $lastActu = $repo->lastByCategory('actualite',$nb);
        $nbEvent = $repo->countByCategory('evenement');
        $lastEvent = $repo->lastByCategory('evenement',$nb);
        $nbPage = $repo->countByCategory('page');
        $lastPage = $repo->lastByCategory('page',$nb);
        $nbSousRubrique = $repo->countByCategory('sous-rubrique');
        $lastSousRubrique = $repo->lastByCategory('sous-rubrique',$nb);

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => compact('nbActu', 'lastActu', 'nbEvent', 'lastEvent', 'nbPage','lastPage', 'nbSousRubrique', 'lastSousRubrique'),
        ]);
    }

    public function newsletterReport() {

        try {
            $mailChimp = new MailChimp($this->getParameter('mailchimp_api_key'));

            $resultList = $mailChimp->get("/lists/{$this->getParameter('mailchimp_list_id')}");

            if (!isset($resultList['status'])) {
                $reportList['Nom de la liste'] = $resultList['name'];
                $reportList['Score d\'activité'] = $resultList['list_rating'];
                $reportList['Nb de membres'] = $resultList['stats']['member_count'];
                $reportList['Nb de désabon.'] = $resultList['stats']['unsubscribe_count'];
                $reportList['Nb moyen d\'abo (mois)'] = $resultList['stats']['avg_sub_rate'];
                $reportList['Nb moyen désabon (mois)'] = $resultList['stats']['avg_unsub_rate'];
                $reportList['Taux d\'ouverture moyen'] = $resultList['stats']['open_rate'];
                $reportList['Taux de clics moyen'] = $resultList['stats']['click_rate'];
            } else {
                $reportList = $resultList;
            }

            $resultCampaigns = $mailChimp->get("reports?count=3");

            if (!isset($resultCampaigns['status'])) {
                foreach($resultCampaigns['reports'] as $key => $resultCampaign) {
                    $reportCampaigns[$key]['Titre'] = $resultCampaign['campaign_title'];
                    $reportCampaigns[$key]['Objet'] = $resultCampaign['subject_line'];
                    $reportCampaigns[$key]['Date'] = date_format(date_create($resultCampaign['send_time']), 'd-m-y H:i:s');
                    $reportCampaigns[$key]['Nb e-mails envoyés'] = $resultCampaign['emails_sent'];
                    $reportCampaigns[$key]['Taux d\'ouverture (%)'] = $resultCampaign['opens']['open_rate'];
                    $reportCampaigns[$key]['Taux de clics'] = $resultCampaign['clicks']['click_rate'];
                }
            } else {
                $reportCampaigns = $resultCampaigns;
            }
        
        } catch (\Exception $e) {
            return $this->render('admin/dashboard/newsletter_report.html.twig', [
                'error' => $e->getMessage()
            ]);
        }

        return $this->render('admin/dashboard/newsletter_report.html.twig', [
            'reportList' => $reportList,
            'reportCampaigns' => $reportCampaigns
        ]);
    }

    public function gaReport()
    {

        try {

            if (file_exists($this->getParameter('kernel.project_dir').$this->getParameter('google_analytics_results_json_file'))) {
                $nowTime = time();
                $gaJsonFileDateLastUpdate = filemtime($this->getParameter('kernel.project_dir').$this->getParameter('google_analytics_results_json_file'));
                $diffTime = $nowTime - $gaJsonFileDateLastUpdate;
            } else {
                $diffTime = false;
            }

            if($diffTime>100 || !$diffTime) {

                $datesStart = [
                    'Les 7 derniers jours' => '7daysAgo',
                    'Les 90 derniers jours' =>  '90daysAgo',
                    'Toutes périodes' => '1460daysAgo'
                ];

                $analyticsService = new GoogleAnalyticsService($this->getParameter('kernel.project_dir').'/'.$this->getParameter('google_service_account_key_file'));
                $viewId = $this->getParameter('google_analytics_view_id');

                $gaResults = array();

                foreach($datesStart as $key => $dateStart) {

                    $gaResults[$key]['users'] = $analyticsService->getUsersDateRange($viewId,$dateStart,'today');
                    $gaResults[$key]['sessions']  = $analyticsService->getSessionsDateRange($viewId,$dateStart,'today');
                    $gaResults[$key]['bounceRate'] = $analyticsService->getBounceRateDateRange($viewId,$dateStart,'today');
                    $gaResults[$key]['avgTimeOnPage'] = $analyticsService->getAvgTimeOnPageDateRange($viewId,$dateStart,'today');
                    $gaResults[$key]['pageViewsPerSession'] = $analyticsService->getPageviewsPerSessionDateRange($viewId,$dateStart,'today');
                    $gaResults[$key]['percentNewVisits'] = $analyticsService->getPercentNewVisitsDateRange($viewId,$dateStart,'today');
                    $gaResults[$key]['pageViews'] = $analyticsService->getPageViewsDateRange($viewId,$dateStart,'today');
                    $gaResults[$key]['avgPageLoadTime'] = $analyticsService->getAvgPageLoadTimeDateRange($viewId,$dateStart,'today');
                    $gaResults[$key]['avgSessionDuration'] = $analyticsService->getAvgSessionDurationDateRange($viewId,$dateStart,'today');
                    $gaResults[$key]['deviceCategoryPerUsers'] = $analyticsService->getDataDateRangeMetricsDimensions($viewId,$dateStart,'today','users',null,'deviceCategory',["fields" => ["users"],"order" => "DESCENDING" ]);
                    $gaResults[$key]['traficSourcePerUsers'] = $analyticsService->getDataDateRangeMetricsDimensions($viewId,$dateStart,'today','users',null,'channelGrouping',["fields" => ["users"],"order" => "DESCENDING" ]);
                    $gaResults[$key]['urlPerViews'] = $analyticsService->getDataDateRangeMetricsDimensions($viewId,$dateStart,'today','pageViews',15,'pagepath',["fields" => ["pageViews"],"order" => "DESCENDING" ]);
                    $gaResults[$key]['cityPerUsers'] = $analyticsService->getDataDateRangeMetricsDimensions($viewId,$dateStart,'today','users',10,'city',["fields" => ["users"],"order" => "DESCENDING" ]);
                    $gaResults[$key]['keywordPerSessions'] = $analyticsService->getDataDateRangeMetricsDimensions($viewId,$dateStart,'today','sessions',10,'keyword',["fields" => ["sessions"],"order" => "DESCENDING" ]);

                }

                $gaResultsInJson = json_encode($gaResults); 
                file_put_contents($this->getParameter('kernel.project_dir').$this->getParameter('google_analytics_results_json_file') ,$gaResultsInJson);
            
            } else {

                $gaResultsInJson = file_get_contents($this->getParameter('kernel.project_dir').$this->getParameter('google_analytics_results_json_file'));
                $gaResults = json_decode($gaResultsInJson, true);

            }
        
        } catch (\Exception $e) {
            $gaResults = $e->getMessage();
        }

        return $this->render('admin/dashboard/ga_report.html.twig', [
            'gaResults' => $gaResults
        ]);

    }
}
