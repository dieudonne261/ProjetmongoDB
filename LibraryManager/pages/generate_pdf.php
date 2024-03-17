<?php
require_once('../tcpdf/tcpdf.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Dede');
$pdf->SetTitle('Liste des Livres');
$pdf->SetSubject('Liste des Livres');
$pdf->SetKeywords('Liste, Livres, PDF');

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->SetFont('helvetica', '', 12);

$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Liste des Livres', 0, 1, 'C');
$pdf->Ln(10);

$html = '<table border="1">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Date d\'édition</th>
                    <th>Code barre</th>
                </tr>
            </thead>
            <tbody>';

$sql = "SELECT titre, auteur, date_ed, code_barre FROM livres";
$stmt = $db->query($sql);
if ($stmt) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $html .= '<tr>';
        $html .= '<td>' . $row['titre'] . '</td>';
        $html .= '<td>' . $row['auteur'] . '</td>';
        $html .= '<td>' . $row['date_ed'] . '</td>';
        $html .= '<td>' . $row['code_barre'] . '</td>';
        $html .= '</tr>';
    }
}

$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('listedeslivres.pdf', 'D');
