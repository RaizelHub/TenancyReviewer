<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Report - {{ $subject->name }}</title>
    <!-- Include jsPDF library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #6366f1;
        }
        .header h1 {
            color: #4f46e5;
            margin-bottom: 5px;
        }
        .header p {
            color: #6b7280;
            margin: 5px 0;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            color: #4f46e5;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .grade-excellent {
            color: #047857;
            font-weight: bold;
        }
        .grade-good {
            color: #0369a1;
            font-weight: bold;
        }
        .grade-passing {
            color: #b45309;
            font-weight: bold;
        }
        .grade-failing {
            color: #dc2626;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.8em;
            color: #6b7280;
        }
        .top-performer {
            background-color: #ecfdf5;
        }
        .summary-box {
            background-color: #f3f4f6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .summary-box h3 {
            margin-top: 0;
            color: #4f46e5;
        }
        .summary-stats {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .stat-item {
            flex: 1;
            min-width: 120px;
            background: white;
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .stat-label {
            font-size: 0.9em;
            color: #6b7280;
        }
        .stat-value {
            font-size: 1.5em;
            font-weight: bold;
            color: #4f46e5;
        }
        .download-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4f46e5;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 15px 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        .download-button:hover {
            background-color: #4338ca;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        .download-button i {
            margin-right: 8px;
        }
        .back-button {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background-color: #6b7280;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 15px 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #4b5563;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        .back-button i {
            margin-right: 8px;
        }
        .student-quiz-details {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #4f46e5;
        }
        .student-quiz-details h5 {
            color: #4f46e5;
            margin-top: 0;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .quiz-passed {
            color: #047857;
            font-weight: bold;
        }
        .quiz-failed {
            color: #dc2626;
            font-weight: bold;
        }
        .premium-badge {
            display: inline-block;
            background-color: #8b5cf6;
            color: white;
            font-size: 0.7em;
            padding: 3px 8px;
            border-radius: 12px;
            margin-left: 8px;
            vertical-align: middle;
        }
        @media print {
            body {
                padding: 0;
            }
            .container {
                box-shadow: none;
            }
            .no-print, .download-button, .back-button {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Final Grade Report</h1>
            <h2>{{ $subject->name }} ({{ $subject->code }})</h2>
            <p>Generated on: {{ $generatedAt }}</p>
        </div>

        <div class="section summary-box">
            <h3>Class Summary</h3>
            <div class="summary-stats">
                <div class="stat-item">
                    <div class="stat-label">Total Students</div>
                    <div class="stat-value">{{ count($allStudents) }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Excellent Students</div>
                    <div class="stat-value">{{ count($excellentStudents) }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Good Students</div>
                    <div class="stat-value">{{ count($goodStudents) }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Low Performing</div>
                    <div class="stat-value">{{ count($lowPerformingStudents) }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Premium Students</div>
                    <div class="stat-value">{{ count($premiumStudents ?? []) }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <h3 class="section-title">Top Performing Students</h3>
            @if(count($topPerformers) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Percentage</th>
                            <th>College Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topPerformers as $index => $data)
                            <tr class="top-performer">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $data['student']->student_id }}</td>
                                <td>{{ $data['student']->name }}</td>
                                <td>{{ $data['grade']['grade'] }}%</td>
                                <td>{{ $data['grade']['college_grade'] }}</td>
                                <td>{{ $data['grade']['remarks'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No top performers found.</p>
            @endif
        </div>

        <div class="section">
            <h3 class="section-title">Excellent Students (1.0 - 1.25)</h3>
            @if(count($excellentStudents) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Percentage</th>
                            <th>College Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($excellentStudents as $data)
                            <tr>
                                <td>{{ $data['student']->student_id }}</td>
                                <td>{{ $data['student']->name }}</td>
                                <td>{{ $data['grade']['grade'] }}%</td>
                                <td class="grade-excellent">{{ $data['grade']['college_grade'] }}</td>
                                <td>{{ $data['grade']['remarks'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No excellent students found.</p>
            @endif
        </div>

        <div class="section">
            <h3 class="section-title">Good Students (1.5 - 2.0)</h3>
            @if(count($goodStudents) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Percentage</th>
                            <th>College Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($goodStudents as $data)
                            <tr>
                                <td>{{ $data['student']->student_id }}</td>
                                <td>{{ $data['student']->name }}</td>
                                <td>{{ $data['grade']['grade'] }}%</td>
                                <td class="grade-good">{{ $data['grade']['college_grade'] }}</td>
                                <td>{{ $data['grade']['remarks'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No good students found.</p>
            @endif
        </div>

        <div class="section">
            <h3 class="section-title">Low Performing Students (5.0)</h3>
            @if(count($lowPerformingStudents) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Percentage</th>
                            <th>College Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowPerformingStudents as $data)
                            <tr>
                                <td>{{ $data['student']->student_id }}</td>
                                <td>{{ $data['student']->name }}</td>
                                <td>{{ $data['grade']['grade'] }}%</td>
                                <td class="grade-failing">{{ $data['grade']['college_grade'] }}</td>
                                <td>{{ $data['grade']['remarks'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No low performing students found.</p>
            @endif
        </div>

        <div class="section">
            <h3 class="section-title">Premium Students Quiz Performance</h3>
            @if(isset($premiumQuizPerformance) && count($premiumQuizPerformance) > 0)
                <div class="summary-box">
                    <h4>Quiz Performance Summary</h4>
                    <div class="summary-stats">
                        <div class="stat-item">
                            <div class="stat-label">Premium Students</div>
                            <div class="stat-value">{{ count($premiumStudents ?? []) }}</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Avg. Quiz Score</div>
                            <div class="stat-value">
                                @php
                                    $avgScore = collect($premiumQuizPerformance)->avg(function($item) {
                                        return $item['quiz_performance']['average_score'];
                                    });
                                @endphp
                                {{ round($avgScore, 1) }}%
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Avg. Completion Rate</div>
                            <div class="stat-value">
                                @php
                                    $avgCompletion = collect($premiumQuizPerformance)->avg(function($item) {
                                        return $item['quiz_performance']['completion_rate'];
                                    });
                                @endphp
                                {{ round($avgCompletion, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Quizzes Attempted</th>
                            <th>Total Attempts</th>
                            <th>Average Score</th>
                            <th>Highest Score</th>
                            <th>Passed Quizzes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($premiumQuizPerformance as $data)
                            <tr>
                                <td>{{ $data['student']->student_id }}</td>
                                <td>{{ $data['student']->name }}</td>
                                <td>{{ $data['quiz_performance']['attempted_quizzes'] }}/{{ $data['quiz_performance']['total_quizzes'] }}</td>
                                <td>{{ $data['quiz_performance']['total_attempts'] }}</td>
                                <td>{{ $data['quiz_performance']['average_score'] }}%</td>
                                <td>{{ $data['quiz_performance']['highest_score'] }}%</td>
                                <td>{{ $data['quiz_performance']['passed_quizzes'] }}/{{ $data['quiz_performance']['attempted_quizzes'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h4 class="section-title">Detailed Quiz Attempts</h4>
                @foreach($premiumQuizPerformance as $data)
                    <div class="student-quiz-details">
                        <h5>{{ $data['student']->name }} ({{ $data['student']->student_id }})</h5>
                        @if(count($data['quiz_performance']['attempts']) > 0)
                            <table>
                                <thead>
                                    <tr>
                                        <th>Quiz Title</th>
                                        <th>Score</th>
                                        <th>Status</th>
                                        <th>Attempts</th>
                                        <th>Time Spent (min)</th>
                                        <th>Completed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['quiz_performance']['attempts'] as $attempt)
                                        <tr>
                                            <td>{{ $attempt['quiz_title'] }}</td>
                                            <td>{{ round($attempt['score'], 1) }}%</td>
                                            <td class="{{ $attempt['passed'] ? 'quiz-passed' : 'quiz-failed' }}">
                                                {{ $attempt['passed'] ? 'Passed' : 'Failed' }}
                                            </td>
                                            <td>{{ $attempt['attempts_count'] }}</td>
                                            <td>{{ $attempt['time_spent'] ?? 'N/A' }}</td>
                                            <td>{{ $attempt['completed_at'] ? $attempt['completed_at']->format('M j, Y g:i A') : 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No quiz attempts found for this student.</p>
                        @endif
                    </div>
                @endforeach
            @else
                <p>No premium students with quiz attempts found.</p>
            @endif
        </div>

        <div class="section">
            <h3 class="section-title">All Students</h3>
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Percentage</th>
                        <th>College Grade</th>
                        <th>Remarks</th>
                        <th>Activities Completed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allStudents as $data)
                        <tr>
                            <td>{{ $data['student']->student_id }}</td>
                            <td>
                                {{ $data['student']->name }}
                                @if($data['student']->hasPremiumAccess())
                                    <span class="premium-badge">Premium</span>
                                @endif
                            </td>
                            <td>{{ $data['grade']['grade'] }}%</td>
                            <td class="{{ $data['grade']['college_grade'] === '5.0' ? 'grade-failing' : ($data['grade']['college_grade'] <= '1.25' ? 'grade-excellent' : ($data['grade']['college_grade'] <= '2.0' ? 'grade-good' : 'grade-passing')) }}">
                                {{ $data['grade']['college_grade'] }}
                            </td>
                            <td>{{ $data['grade']['remarks'] }}</td>
                            <td>{{ $data['grade']['completed_activities'] }}/{{ $data['grade']['total_activities'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>This is an official grade report for {{ $subject->name }}.</p>
            <p>© {{ date('Y') }} {{ tenant('name') ?? config('app.name') }}. All rights reserved.</p>
        </div>
    </div>

    <!-- Download Button -->
    <button id="downloadPdf" class="download-button">
        <i class="fas fa-file-download"></i> Download PDF
    </button>

    <!-- Back Button -->
    <a href="{{ route('subjects.show', $subject->id) }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Back to Subject
    </a>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- PDF Generation Script -->
    <script>
        // Wait for the document to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Get the download button
            const downloadBtn = document.getElementById('downloadPdf');

            // Add click event listener
            downloadBtn.addEventListener('click', function() {
                // Change button text to show loading
                downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating PDF...';
                downloadBtn.disabled = true;

                // Hide the buttons for the PDF capture
                downloadBtn.style.display = 'none';
                document.querySelector('.back-button').style.display = 'none';

                // Use html2canvas to capture the container
                setTimeout(function() {
                    const { jsPDF } = window.jspdf;

                    // Create a new jsPDF instance
                    const pdf = new jsPDF('p', 'pt', 'a4');
                    const container = document.querySelector('.container');
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = pdf.internal.pageSize.getHeight();
                    const margins = 40;

                    // Function to add pages
                    const addPageToPdf = function(canvas, pdf, pdfWidth, pdfHeight, margins, pageNumber) {
                        // If not the first page, add a new page
                        if (pageNumber > 0) {
                            pdf.addPage();
                        }

                        // Calculate the scale to fit the width
                        const imgWidth = canvas.width;
                        const imgHeight = canvas.height;
                        const ratio = Math.min((pdfWidth - 2 * margins) / imgWidth, 1);

                        // Add the image to the PDF
                        const imgData = canvas.toDataURL('image/png');
                        pdf.addImage(imgData, 'PNG', margins, margins, imgWidth * ratio, imgHeight * ratio);
                    };

                    // Function to handle multi-page PDF generation
                    const generatePDF = async function() {
                        try {
                            // Get all sections
                            const sections = document.querySelectorAll('.section');
                            const header = document.querySelector('.header');
                            const footer = document.querySelector('.footer');

                            // First, add the header
                            const headerCanvas = await html2canvas(header, {
                                scale: 2,
                                useCORS: true,
                                logging: false,
                                letterRendering: true
                            });

                            // Add header to first page
                            const headerImgData = headerCanvas.toDataURL('image/png');
                            const headerRatio = Math.min((pdfWidth - 2 * margins) / headerCanvas.width, 1);
                            pdf.addImage(
                                headerImgData,
                                'PNG',
                                margins,
                                margins,
                                headerCanvas.width * headerRatio,
                                headerCanvas.height * headerRatio
                            );

                            // Track vertical position
                            let yPosition = margins + (headerCanvas.height * headerRatio) + 20;

                            // Process each section
                            for (let i = 0; i < sections.length; i++) {
                                const section = sections[i];
                                const sectionCanvas = await html2canvas(section, {
                                    scale: 2,
                                    useCORS: true,
                                    logging: false,
                                    letterRendering: true
                                });

                                const sectionRatio = Math.min((pdfWidth - 2 * margins) / sectionCanvas.width, 1);
                                const sectionHeight = sectionCanvas.height * sectionRatio;

                                // Check if we need a new page
                                if (yPosition + sectionHeight > pdfHeight - margins) {
                                    pdf.addPage();
                                    yPosition = margins;
                                }

                                // Add section to PDF
                                const sectionImgData = sectionCanvas.toDataURL('image/png');
                                pdf.addImage(
                                    sectionImgData,
                                    'PNG',
                                    margins,
                                    yPosition,
                                    sectionCanvas.width * sectionRatio,
                                    sectionHeight
                                );

                                // Update position
                                yPosition += sectionHeight + 20;
                            }

                            // Add footer to last page if there's room
                            if (footer) {
                                const footerCanvas = await html2canvas(footer, {
                                    scale: 2,
                                    useCORS: true,
                                    logging: false,
                                    letterRendering: true
                                });

                                const footerRatio = Math.min((pdfWidth - 2 * margins) / footerCanvas.width, 1);
                                const footerHeight = footerCanvas.height * footerRatio;

                                // Check if we need a new page for footer
                                if (yPosition + footerHeight > pdfHeight - margins) {
                                    pdf.addPage();
                                    yPosition = margins;
                                }

                                // Add footer to PDF
                                const footerImgData = footerCanvas.toDataURL('image/png');
                                pdf.addImage(
                                    footerImgData,
                                    'PNG',
                                    margins,
                                    yPosition,
                                    footerCanvas.width * footerRatio,
                                    footerHeight
                                );
                            }

                            // Save the PDF
                            pdf.save('grade-report-{{ $subject->name }}.pdf');

                        } catch (error) {
                            console.error('Error generating PDF:', error);
                            alert('There was an error generating the PDF. Please try again.');
                        } finally {
                            // Show the buttons again
                            downloadBtn.style.display = 'flex';
                            downloadBtn.innerHTML = '<i class="fas fa-file-download"></i> Download PDF';
                            downloadBtn.disabled = false;
                            document.querySelector('.back-button').style.display = 'flex';
                        }
                    };

                    // Start PDF generation
                    generatePDF();
                }, 500);
            });
        });
    </script>
</body>
</html>
