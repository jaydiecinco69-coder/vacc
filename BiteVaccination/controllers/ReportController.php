<?php
require_once __DIR__ . '/../core/Controller.php';

class ReportController extends Controller {
    private $reportModel;
    private $patientModel;
    private $appointmentModel;
    private $vaccinationModel;
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->reportModel = $this->model('Report');
        $this->patientModel = $this->model('Patient');
        $this->appointmentModel = $this->model('Appointment');
        $this->vaccinationModel = $this->model('Vaccination');
        $this->userModel = $this->model('User');
    }
    
    public function index() {
        $this->view('reports/index');
    }
    
    public function generate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reportType = $_POST['report_type'];
            $dateFrom = $_POST['date_from'];
            $dateTo = $_POST['date_to'];
            $format = $_POST['format'] ?? 'web';
            
            $errors = $this->validateReportRequest($reportType, $dateFrom, $dateTo);
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                $this->view('reports/index');
                return;
            }
            
            $reportData = $this->generateReportData($reportType, $dateFrom, $dateTo);
            
            if ($format === 'pdf') {
                $this->generatePDF($reportType, $reportData, $dateFrom, $dateTo);
            } else {
                $this->view('reports/view', [
                    'reportType' => $reportType,
                    'reportData' => $reportData,
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo
                ]);
            }
        } else {
            $this->redirect('reports');
        }
    }
    
    public function daily() {
        $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
        
        $reportData = [
            'patients' => $this->patientModel->getByDate($date),
            'appointments' => $this->appointmentModel->getByDate($date),
            'vaccinations' => $this->vaccinationModel->getByDate($date),
            'summary' => $this->getDailySummary($date)
        ];
        
        $this->view('reports/daily', [
            'reportData' => $reportData,
            'date' => $date
        ]);
    }
    
    public function monthly() {
        $month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
        
        $reportData = [
            'patients' => $this->patientModel->getByMonth($year, $month),
            'appointments' => $this->appointmentModel->getByMonth($year, $month),
            'vaccinations' => $this->vaccinationModel->getByMonth($year, $month),
            'summary' => $this->getMonthlySummary($year, $month)
        ];
        
        $this->view('reports/monthly', [
            'reportData' => $reportData,
            'month' => $month,
            'year' => $year
        ]);
    }
    
    public function vaccination() {
        $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-01');
        $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d');
        
        $reportData = [
            'vaccinations' => $this->vaccinationModel->getVaccinationReport($dateFrom, $dateTo),
            'statistics' => $this->getVaccinationStatistics($dateFrom, $dateTo),
            'adverseReactions' => $this->vaccinationModel->getAdverseReactionsByDate($dateFrom, $dateTo)
        ];
        
        $this->view('reports/vaccination', [
            'reportData' => $reportData,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo
        ]);
    }
    
    public function patient() {
        $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-01');
        $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d');
        
        $reportData = [
            'patients' => $this->patientModel->getPatientReport($dateFrom, $dateTo),
            'statistics' => $this->getPatientStatistics($dateFrom, $dateTo),
            'ageDistribution' => $this->patientModel->getAgeDistribution(),
            'genderDistribution' => $this->patientModel->getGenderDistribution()
        ];
        
        $this->view('reports/patient', [
            'reportData' => $reportData,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo
        ]);
    }
    
    public function export() {
        $reportType = $_GET['type'];
        $dateFrom = $_GET['date_from'];
        $dateTo = $_GET['date_to'];
        $format = $_GET['format'] ?? 'csv';
        
        $reportData = $this->generateReportData($reportType, $dateFrom, $dateTo);
        
        if ($format === 'csv') {
            $this->exportToCSV($reportType, $reportData);
        } elseif ($format === 'excel') {
            $this->exportToExcel($reportType, $reportData);
        } else {
            $this->generatePDF($reportType, $reportData, $dateFrom, $dateTo);
        }
    }
    
    private function generateReportData($reportType, $dateFrom, $dateTo) {
        switch ($reportType) {
            case 'daily':
                return [
                    'patients' => $this->patientModel->getByDateRange($dateFrom, $dateTo),
                    'appointments' => $this->appointmentModel->getByDateRange($dateFrom, $dateTo),
                    'vaccinations' => $this->vaccinationModel->getByDateRange($dateFrom, $dateTo),
                    'summary' => $this->getSummary($dateFrom, $dateTo)
                ];
                
            case 'vaccination':
                return [
                    'vaccinations' => $this->vaccinationModel->getVaccinationReport($dateFrom, $dateTo),
                    'statistics' => $this->getVaccinationStatistics($dateFrom, $dateTo),
                    'adverseReactions' => $this->vaccinationModel->getAdverseReactionsByDate($dateFrom, $dateTo)
                ];
                
            case 'patient':
                return [
                    'patients' => $this->patientModel->getPatientReport($dateFrom, $dateTo),
                    'statistics' => $this->getPatientStatistics($dateFrom, $dateTo),
                    'ageDistribution' => $this->patientModel->getAgeDistribution(),
                    'genderDistribution' => $this->patientModel->getGenderDistribution()
                ];
                
            case 'appointment':
                return [
                    'appointments' => $this->appointmentModel->getAppointmentReport($dateFrom, $dateTo),
                    'statistics' => $this->getAppointmentStatistics($dateFrom, $dateTo),
                    'statusDistribution' => $this->appointmentModel->getStatusDistribution($dateFrom, $dateTo)
                ];
                
            default:
                return [];
        }
    }
    
    private function generatePDF($reportType, $reportData, $dateFrom, $dateTo) {
        require_once __DIR__ . '/../vendor/tcpdf/tcpdf.php';
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetCreator('Animal Bite Center Management System');
        $pdf->SetAuthor('Animal Bite Center');
        $pdf->SetTitle(ucfirst($reportType) . ' Report');
        
        $pdf->AddPage();
        
        // Header
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Animal Bite Center Management System', 0, 1, 'C');
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, ucfirst($reportType) . ' Report', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 10, 'Period: ' . date('M d, Y', strtotime($dateFrom)) . ' - ' . date('M d, Y', strtotime($dateTo)), 0, 1, 'C');
        $pdf->Ln(10);
        
        // Report content based on type
        switch ($reportType) {
            case 'vaccination':
                $this->addVaccinationReportToPDF($pdf, $reportData);
                break;
            case 'patient':
                $this->addPatientReportToPDF($pdf, $reportData);
                break;
            case 'appointment':
                $this->addAppointmentReportToPDF($pdf, $reportData);
                break;
            default:
                $this->addGeneralReportToPDF($pdf, $reportData);
                break;
        }
        
        // Footer
        $pdf->SetY(-15);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 10, 'Generated on ' . date('Y-m-d H:i:s'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        $filename = $reportType . '_report_' . date('Y-m-d') . '.pdf';
        $pdf->Output($filename, 'D');
    }
    
    private function addVaccinationReportToPDF($pdf, $reportData) {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Vaccination Statistics', 0, 1);
        $pdf->Ln(5);
        
        $pdf->SetFont('helvetica', '', 10);
        foreach ($reportData['statistics'] as $key => $value) {
            $pdf->Cell(60, 6, ucfirst(str_replace('_', ' ', $key)) . ':', 0, 0);
            $pdf->Cell(40, 6, $value, 0, 1);
        }
        
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Vaccination Details', 0, 1);
        $pdf->Ln(5);
        
        // Table headers
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(30, 6, 'Date', 1, 0, 'C');
        $pdf->Cell(40, 6, 'Patient', 1, 0, 'C');
        $pdf->Cell(30, 6, 'Vaccine', 1, 0, 'C');
        $pdf->Cell(20, 6, 'Dose', 1, 0, 'C');
        $pdf->Cell(30, 6, 'Status', 1, 0, 'C');
        $pdf->Cell(40, 6, 'Administered By', 1, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 9);
        foreach ($reportData['vaccinations'] as $vaccination) {
            $pdf->Cell(30, 6, date('M d, Y', strtotime($vaccination['administration_date'])), 1, 0, 'C');
            $pdf->Cell(40, 6, $vaccination['first_name'] . ' ' . $vaccination['last_name'], 1, 0, 'C');
            $pdf->Cell(30, 6, ucfirst($vaccination['vaccine_type']), 1, 0, 'C');
            $pdf->Cell(20, 6, $vaccination['dose_number'], 1, 0, 'C');
            $pdf->Cell(30, 6, ucfirst($vaccination['status']), 1, 0, 'C');
            $pdf->Cell(40, 6, $vaccination['administered_by_name'] ?: 'N/A', 1, 1, 'C');
        }
    }
    
    private function addPatientReportToPDF($pdf, $reportData) {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Patient Statistics', 0, 1);
        $pdf->Ln(5);
        
        $pdf->SetFont('helvetica', '', 10);
        foreach ($reportData['statistics'] as $key => $value) {
            $pdf->Cell(60, 6, ucfirst(str_replace('_', ' ', $key)) . ':', 0, 0);
            $pdf->Cell(40, 6, $value, 0, 1);
        }
        
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Patient Details', 0, 1);
        $pdf->Ln(5);
        
        // Table headers
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(25, 6, 'Patient ID', 1, 0, 'C');
        $pdf->Cell(50, 6, 'Name', 1, 0, 'C');
        $pdf->Cell(30, 6, 'Age', 1, 0, 'C');
        $pdf->Cell(30, 6, 'Gender', 1, 0, 'C');
        $pdf->Cell(30, 6, 'Blood Type', 1, 0, 'C');
        $pdf->Cell(40, 6, 'Phone', 1, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 9);
        foreach ($reportData['patients'] as $patient) {
            $age = date_diff(date_create($patient['birth_date']), date_create('now'))->y;
            $pdf->Cell(25, 6, $patient['patient_id'], 1, 0, 'C');
            $pdf->Cell(50, 6, $patient['first_name'] . ' ' . $patient['last_name'], 1, 0, 'C');
            $pdf->Cell(30, 6, $age . ' years', 1, 0, 'C');
            $pdf->Cell(30, 6, ucfirst($patient['gender']), 1, 0, 'C');
            $pdf->Cell(30, 6, $patient['blood_type'] ?: 'N/A', 1, 0, 'C');
            $pdf->Cell(40, 6, $patient['phone'], 1, 1, 'C');
        }
    }
    
    private function exportToCSV($reportType, $reportData) {
        $filename = $reportType . '_report_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add CSV headers based on report type
        switch ($reportType) {
            case 'vaccination':
                fputcsv($output, ['Date', 'Patient ID', 'Patient Name', 'Vaccine Type', 'Dose', 'Status', 'Administered By']);
                foreach ($reportData['vaccinations'] as $vaccination) {
                    fputcsv($output, [
                        $vaccination['administration_date'],
                        $vaccination['patient_id'],
                        $vaccination['first_name'] . ' ' . $vaccination['last_name'],
                        $vaccination['vaccine_type'],
                        $vaccination['dose_number'],
                        $vaccination['status'],
                        $vaccination['administered_by_name']
                    ]);
                }
                break;
                
            case 'patient':
                fputcsv($output, ['Patient ID', 'First Name', 'Last Name', 'Birth Date', 'Gender', 'Blood Type', 'Phone', 'Email', 'Address']);
                foreach ($reportData['patients'] as $patient) {
                    fputcsv($output, [
                        $patient['patient_id'],
                        $patient['first_name'],
                        $patient['last_name'],
                        $patient['birth_date'],
                        $patient['gender'],
                        $patient['blood_type'],
                        $patient['phone'],
                        $patient['email'],
                        $patient['address']
                    ]);
                }
                break;
        }
        
        fclose($output);
        exit;
    }
    
    private function validateReportRequest($reportType, $dateFrom, $dateTo) {
        $errors = [];
        
        if (empty($reportType)) {
            $errors[] = 'Report type is required.';
        }
        
        if (empty($dateFrom)) {
            $errors[] = 'Start date is required.';
        } elseif (!strtotime($dateFrom)) {
            $errors[] = 'Invalid start date format.';
        }
        
        if (empty($dateTo)) {
            $errors[] = 'End date is required.';
        } elseif (!strtotime($dateTo)) {
            $errors[] = 'Invalid end date format.';
        } elseif (strtotime($dateTo) < strtotime($dateFrom)) {
            $errors[] = 'End date must be after start date.';
        }
        
        return $errors;
    }
    
    private function getSummary($dateFrom, $dateTo) {
        return [
            'total_patients' => $this->patientModel->countByDateRange($dateFrom, $dateTo),
            'total_appointments' => $this->appointmentModel->countByDateRange($dateFrom, $dateTo),
            'total_vaccinations' => $this->vaccinationModel->countByDateRange($dateFrom, $dateTo),
            'completed_vaccinations' => $this->vaccinationModel->countCompletedByDateRange($dateFrom, $dateTo)
        ];
    }
    
    private function getVaccinationStatistics($dateFrom, $dateTo) {
        return [
            'total_vaccinations' => $this->vaccinationModel->countByDateRange($dateFrom, $dateTo),
            'anti_rabies_count' => $this->vaccinationModel->countByTypeAndDateRange('anti_rabies', $dateFrom, $dateTo),
            'tetanus_count' => $this->vaccinationModel->countByTypeAndDateRange('tetanus', $dateFrom, $dateTo),
            'immunoglobulin_count' => $this->vaccinationModel->countByTypeAndDateRange('immunoglobulin', $dateFrom, $dateTo),
            'adverse_reactions_count' => $this->vaccinationModel->countAdverseReactionsByDateRange($dateFrom, $dateTo)
        ];
    }
    
    private function getPatientStatistics($dateFrom, $dateTo) {
        return [
            'new_patients' => $this->patientModel->countByDateRange($dateFrom, $dateTo),
            'total_patients' => $this->patientModel->count(),
            'average_age' => $this->patientModel->getAverageAge(),
            'male_patients' => $this->patientModel->countByGender('male'),
            'female_patients' => $this->patientModel->countByGender('female')
        ];
    }
    
    private function getAppointmentStatistics($dateFrom, $dateTo) {
        return [
            'total_appointments' => $this->appointmentModel->countByDateRange($dateFrom, $dateTo),
            'completed_appointments' => $this->appointmentModel->countByStatusAndDateRange('completed', $dateFrom, $dateTo),
            'cancelled_appointments' => $this->appointmentModel->countByStatusAndDateRange('cancelled', $dateFrom, $dateTo),
            'no_show_appointments' => $this->appointmentModel->countByStatusAndDateRange('no_show', $dateFrom, $dateTo)
        ];
    }
}
?>
