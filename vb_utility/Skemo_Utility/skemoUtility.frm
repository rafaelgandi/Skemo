VERSION 5.00
Begin VB.Form skemoForm 
   BackColor       =   &H00E0E0E0&
   Caption         =   "Skemo™  Utility"
   ClientHeight    =   5025
   ClientLeft      =   165
   ClientTop       =   525
   ClientWidth     =   6210
   Icon            =   "skemoUtility.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   ScaleHeight     =   335
   ScaleMode       =   3  'Pixel
   ScaleWidth      =   414
   StartUpPosition =   2  'CenterScreen
   WindowState     =   1  'Minimized
   Begin VB.Timer sysTimeDelay 
      Interval        =   1000
      Left            =   480
      Top             =   0
   End
   Begin VB.Timer sysTimer 
      Enabled         =   0   'False
      Interval        =   1000
      Left            =   0
      Top             =   0
   End
   Begin VB.Label Label6 
      BackColor       =   &H00E0E0E0&
      BackStyle       =   0  'Transparent
      Caption         =   "Skemo"
      BeginProperty Font 
         Name            =   "Tahoma"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   255
      Left            =   4920
      TabIndex        =   10
      Top             =   4320
      Width           =   615
   End
   Begin VB.Label Label3 
      BackColor       =   &H00E0E0E0&
      BackStyle       =   0  'Transparent
      Caption         =   "powered  by               Team"
      BeginProperty Font 
         Name            =   "Tahoma"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   3840
      TabIndex        =   9
      Top             =   4320
      Width           =   2535
   End
   Begin VB.Shape Shape1 
      FillColor       =   &H00004000&
      FillStyle       =   0  'Solid
      Height          =   375
      Left            =   0
      Top             =   4680
      Width           =   6495
   End
   Begin VB.Label sysCtrLbl 
      BackColor       =   &H00E0E0E0&
      BackStyle       =   0  'Transparent
      Caption         =   "60"
      BeginProperty Font 
         Name            =   "Verdana"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   255
      Left            =   1920
      TabIndex        =   8
      Top             =   3360
      Width           =   1575
   End
   Begin VB.Label Label8 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00E0E0E0&
      BackStyle       =   0  'Transparent
      Caption         =   "Performing Next Action In"
      BeginProperty Font 
         Name            =   "Verdana"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   0
      TabIndex        =   7
      Top             =   3120
      Width           =   1815
   End
   Begin VB.Label enrold_statusLbl 
      BackColor       =   &H00E0E0E0&
      Caption         =   "Queued"
      BeginProperty Font 
         Name            =   "Verdana"
         Size            =   11.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   495
      Left            =   4320
      TabIndex        =   6
      Top             =   2160
      Width           =   1455
   End
   Begin VB.Label student_statusLbl 
      BackColor       =   &H00E0E0E0&
      Caption         =   "Queued"
      BeginProperty Font 
         Name            =   "Verdana"
         Size            =   11.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   495
      Left            =   4320
      TabIndex        =   5
      Top             =   1440
      Width           =   1335
   End
   Begin VB.Label Label5 
      BackColor       =   &H00E0E0E0&
      Caption         =   "Fetching Students Table:"
      BeginProperty Font 
         Name            =   "Verdana"
         Size            =   11.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00404040&
      Height          =   375
      Left            =   120
      TabIndex        =   4
      Top             =   1440
      Width           =   2775
   End
   Begin VB.Label Label4 
      BackColor       =   &H00E0E0E0&
      Caption         =   "Fetching Subjects Enrolled Table:"
      BeginProperty Font 
         Name            =   "Verdana"
         Size            =   11.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00404040&
      Height          =   375
      Left            =   120
      TabIndex        =   3
      Top             =   2160
      Width           =   3735
   End
   Begin VB.Label subject_statusLbl 
      BackColor       =   &H00E0E0E0&
      Caption         =   "Processing..."
      BeginProperty Font 
         Name            =   "Verdana"
         Size            =   11.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   375
      Left            =   4320
      TabIndex        =   2
      Top             =   720
      Width           =   1575
   End
   Begin VB.Label Label2 
      BackColor       =   &H00E0E0E0&
      Caption         =   "Status"
      BeginProperty Font 
         Name            =   "Tahoma"
         Size            =   11.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   4320
      TabIndex        =   1
      Top             =   120
      Width           =   735
   End
   Begin VB.Label Label1 
      BackColor       =   &H00E0E0E0&
      Caption         =   "Fetching Subjects Table:"
      BeginProperty Font 
         Name            =   "Verdana"
         Size            =   11.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00404040&
      Height          =   375
      Left            =   120
      TabIndex        =   0
      Top             =   720
      Width           =   2775
   End
   Begin VB.Menu skemoMenu 
      Caption         =   "Popup"
      Visible         =   0   'False
      Begin VB.Menu popMenuShow 
         Caption         =   "&Show Skemo™ Utility"
      End
      Begin VB.Menu popMenuExit 
         Caption         =   "&Exit"
      End
   End
End
Attribute VB_Name = "skemoForm"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Public Sub dataUpdater(tableName As String)
Dim excelPath As String

Dim sql As String
excelPath = App.Path + "\EDP_TABLES\" + tableName
conn_Excel.Open "Driver={Microsoft Excel Driver (*.xls)};Dbq=" + excelPath

Select Case tableCtrIndicator
 
 Case 1 ' QUERY SUBJECTS_TABLE
  sql = "SELECT * FROM [Sheet1$] WHERE space <> 0 AND term = 200821"
  rs_Excel.Open sql, conn_Excel
 
  Do Until rs_Excel.EOF
    offerNO = rs_Excel!offer_no ' the same as rs_Excel.Field.Item("Term")
   subjNO = rs_Excel!subject_no
   subjID = rs_Excel!subject_id
   subjDesc = rs_Excel!subject_title
   subjTime = rs_Excel!Time
   subjDay = rs_Excel!Day
   subjRoom = rs_Excel!room
   subjTerm = rs_Excel!term
  
   sql = "INSERT INTO subjects_table VALUES(" & offerNO & ",'" _
        & subjID & "','" & subjNO & "','" & subjDesc & "','" & subjTime & "'," & subjDay & ",'" & subjRoom & "','" & subjTerm & "')"
   Call feedDatabase(sql)
   rs_Excel.MoveNext
   
  Loop
  
   
 Case 2 ' QUERY STUDENTS_TABLE
  sql = "SELECT * FROM [Sheet1$]"
  rs_Excel.Open sql, conn_Excel
  
  Do Until rs_Excel.EOF
  
   studNO = rs_Excel!studentid
   fname = rs_Excel!firstname
   mname = rs_Excel.Fields(2).Value
   lname = rs_Excel!lastname
   course = rs_Excel!course
   yrLevel = rs_Excel!Year
   curriculum = rs_Excel!curriculum
   
   sql = "INSERT INTO student_table VALUES(" & studNO & ",'" _
        & fname & "','" & mname & "','" & lname & "','" & course & "'," & yrLevel & "," & curriculum & ")"
   Call feedDatabase(sql)
   rs_Excel.MoveNext
   
  Loop
   
Case 3 ' QUERY SUBJECTS_ENROLLED
  sql = "SELECT * FROM [Sheet1$]"
  rs_Excel.Open sql, conn_Excel
  
  Do Until rs_Excel.EOF
   studNO = rs_Excel!student_id
   subjOffer = rs_Excel!offer_no
   subjID = rs_Excel!subject_id
   subjNO = rs_Excel!subject_no
   subjGrade = rs_Excel!grade
   
   If IsNull(subjGrade) Or subjGrade = "" Then
    subjGrade = 0
   End If
   
   sql = "INSERT INTO subjects_enrolled VALUES(" & studNO & ",'" _
        & subjOffer & "','" & subjID & "','" & subjNO & "'," & subjGrade & ")"
   Call feedDatabase(sql)
   rs_Excel.MoveNext
   
  Loop
  
End Select
 sql = ""
 rs_Excel.Close
 conn_Excel.Close
 
End Sub
Private Sub sysTimer_Timer()

secs = secs - 1
  
 If secs = 0 Then
  mins = mins - 1
  secs = 59
 End If
 
 If mins < 0 Then ' DONE RESTING FOR 1 HR, PERFORM TASK AGAIN
  mins = 59
  Call initializer("BEGIN_SYSTEM")
 End If
 
 secs = Format(secs, "00")
 mins = Format(mins, "00")
 
 sysCtrLbl.Caption = "00:" & mins & ":" & secs

End Sub
'Private Sub sysTimeDelay_Timer()
' If (bug <> 1) Then
'  Call statLblChange(subject_statusLbl, "PRO")
' End If

'End Sub
Public Sub tableFetching()

 Select Case (tableCtrIndicator)
  Case 1
   Call statLblChange(subject_statusLbl, "PRO")
   Call statLblChange(student_statusLbl, "QUE")
   Call statLblChange(enrold_statusLbl, "QUE")
   dataUpdater ("SUBJECTS TABLE.XLS")
   tableCtrIndicator = 2
  Case 2
   Call statLblChange(subject_statusLbl, "FIN")
   Call statLblChange(student_statusLbl, "PRO")
   dataUpdater ("STUDENT TABLE.XLS")
   tableCtrIndicator = 3
  Case 3
   Call statLblChange(student_statusLbl, "FIN")
   Call statLblChange(enrold_statusLbl, "PRO")
   dataUpdater ("SUBJECTS ENROLLED.XLS")
 End Select
  
End Sub
Public Sub statLblChange(lblName As Object, status As String)
 Select Case (status)
  Case "PRO"
   lblName.Caption = "Processing..."
  Case "FIN"
   lblName.Caption = "Finished"
   lblName.ForeColor = &H80FF&
  Case "WAIT"
   lblName.Caption = "Waiting..."
   lblName.ForeColor = &H800000
  Case "QUE"
   lblName.Caption = "Queued"
   lblName.ForeColor = &HFF&
 End Select
End Sub

Public Sub feedDatabase(sqlQuery)
Dim err As Error

On Error GoTo ErrorHandler

rs_SQL.Open sqlQuery, conn_SQL

ErrorHandler:

    'For Each err In conn_SQL.Errors
        ' PERFORM NOTHING HERE
        'Debug.Print err.Description
        'MsgBox "Error" & err.Description
    'Next

    Resume Next
    
End Sub

Public Sub initializer(typeOfAction As String)

Select Case typeOfAction

 Case "BEGIN_SYSTEM"
 
  '-- SET ALL CONNECTORS
  Set conn_SQL = New ADODB.Connection
  Set rs_SQL = New ADODB.Recordset

  Set conn_Excel = New ADODB.Connection
  Set rs_Excel = New ADODB.Recordset
  '-- END OF CONNECTORS
  
  'sysTimerCtr = 0
  secs = 0
  mins = 0
  sysTimer.Enabled = False    ' STOP TIMER FROM TICKING
 
                      '-- INIT DATABASE CONNECTION
  conn_SQL.Open "DRIVER={MySQL ODBC 3.51 Driver};" _
            & "SERVER=127.0.0.1;" _
            & "DATABASE=skemo;" _
            & "UID=root;" _
            & "PWD=;"
 
                      '-- END OF MYSQL_DB CONNECTION
                      
                       '-- SET STATUS LBL
  Call statLblChange(subject_statusLbl, "WAIT")
  Call statLblChange(student_statusLbl, "WAIT")
  Call statLblChange(enrold_statusLbl, "WAIT")
                     '-- END OF SET STATUS

  tableCtrIndicator = 1 ' FETCH SUBJECT_TABLE.XLS FIRST
  
  sqlStatement = "TRUNCATE subjects_table"
  Call feedDatabase(sqlStatement)
  Call tableFetching
  
  sqlStatement = "TRUNCATE student_table"
  Call feedDatabase(sqlStatement)
  Call tableFetching
  
  sqlStatement = "TRUNCATE subjects_enrolled"
  Call feedDatabase(sqlStatement)
  Call tableFetching
  
  Call initializer("END_SYSTEM")    ' DONE PERFORMING ALL TASK, NOW END IT
  
Case "END_SYSTEM"

 '-- STOP SQL DB CONNECTION
  'conn_SQL.Close
  'rs_SQL.Close
  Set rs_SQL = Nothing
  Set conn_SQL = Nothing
  Set conn_Excel = Nothing
  Set rs_Excel = Nothing
 '-- END OF DB CONNECTION
 
 Call statLblChange(subject_statusLbl, "WAIT")
 Call statLblChange(student_statusLbl, "WAIT")
 Call statLblChange(enrold_statusLbl, "WAIT")
 
 secs = 59   ' INIT SECONDS
 mins = 59   ' INIT MINUTES
 
 sysTimer.Enabled = True    ' BEGIN COUNTING FOR NEXT PERFORMANCE
 
End Select
End Sub

Private Sub Form_Load()
 Me.Hide
 'Me.Show
 Call init_sysIconDisplay        ' DISPLAY FORM INTO SYS TRAY
 Call initializer("BEGIN_SYSTEM")
End Sub

Private Sub Form_MouseMove(Button As Integer, Shift As Integer, x As Single, Y As Single)
Dim mouseEvent As Long

mouseEvent = x

If mouseEvent = WM_RBUTTONDOWN Then         ' RIGHT MOUSE CLICK
 PopupMenu skemoMenu                        ' SHOW POP MENU IN SYS TRAY
ElseIf mouseEvent = WM_LBUTTONDBLCLK Then   ' LEFT DBL CLICK
 Me.WindowState = 0                         ' BRING TO FRONT
 Me.Show                                    ' SHOW HIDDEN FORM
End If

End Sub

Private Sub Form_Resize()

If Me.WindowState = 1 Then  ' WINDOW WAS MINIMIZED
 Me.Hide
End If

End Sub

Private Sub Form_Unload(Cancel As Integer)
 Dim ans
 
 ansMsgBox = MsgBox("Closing This Form Will Stop The System From Fetching Excel Data." & vbCrLf & "Proceed?", vbYesNo + vbExclamation, "Skemo™ Utility: Caution")
 
 If ansMsgBox = 6 Then ' USER CLICK YES BUTTON
 'confirmPass.Show
  Call initializer("END_SYSTEM")
  Call Shell_NotifyIcon(NIM_DELETE, iconData)
  Unload Me
 Else
  Cancel = 1     ' DONT SHUT THE WINDOW
 End If
 
End Sub

Private Sub popMenuExit_Click()
 Call Form_Unload(0)
End Sub

Private Sub popMenuShow_Click()
 Me.WindowState = 0
 Me.Show
End Sub

Public Sub init_sysIconDisplay()
    
    iconData.uSize = Len(iconData)
    iconData.uHandle = skemoForm.hWnd
    iconData.uID = vbNull
    iconData.uFlags = NIF_TIP Or NIF_ICON Or NIF_MESSAGE
    iconData.uCallbackMessage = WM_MOUSEMOVE
    iconData.uIcon = skemoForm.Icon
    iconData.uTip = "Skemo™ Parser Utility" & Chr(0)
    Call Shell_NotifyIcon(NIM_ADD, iconData)
    
End Sub
