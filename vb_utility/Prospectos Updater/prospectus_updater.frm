VERSION 5.00
Object = "{F9043C88-F6F2-101A-A3C9-08002B2F49FB}#1.2#0"; "comdlg32.ocx"
Begin VB.Form Propectus_Updater 
   BackColor       =   &H8000000A&
   Caption         =   "Prospectus Updater"
   ClientHeight    =   2625
   ClientLeft      =   60
   ClientTop       =   420
   ClientWidth     =   5790
   Icon            =   "prospectus_updater.frx":0000
   LinkTopic       =   "Form1"
   ScaleHeight     =   2625
   ScaleWidth      =   5790
   StartUpPosition =   2  'CenterScreen
   Begin VB.CommandButton cmdExtract 
      Caption         =   "&Process"
      BeginProperty Font 
         Name            =   "Tahoma"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   3960
      TabIndex        =   2
      Top             =   960
      Width           =   1335
   End
   Begin VB.TextBox fileTxt 
      BackColor       =   &H80000009&
      BeginProperty Font 
         Name            =   "Tahoma"
         Size            =   11.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   240
      Locked          =   -1  'True
      TabIndex        =   1
      Text            =   "Select Excel File"
      Top             =   360
      Width           =   3495
   End
   Begin VB.CommandButton cmdBrowse 
      Caption         =   "&Browse File"
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
      Left            =   3960
      TabIndex        =   0
      Top             =   360
      Width           =   1335
   End
   Begin MSComDlg.CommonDialog CommonDialog 
      Left            =   240
      Top             =   840
      _ExtentX        =   847
      _ExtentY        =   847
      _Version        =   393216
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
      Left            =   3480
      TabIndex        =   4
      Top             =   1920
      Width           =   2535
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
      Left            =   4560
      TabIndex        =   3
      Top             =   1920
      Width           =   615
   End
   Begin VB.Shape Shape1 
      FillColor       =   &H00404040&
      FillStyle       =   0  'Solid
      Height          =   495
      Left            =   0
      Top             =   2280
      Width           =   6135
   End
End
Attribute VB_Name = "Propectus_Updater"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Dim conn1 As New ADODB.Connection ' CONNECTION FOR EXCEL
Dim rs1 As New ADODB.Recordset    ' RECORDSET HOLDER FOR EXCEL
Dim rs2 As New ADODB.Recordset    ' RECORDSET HOLDER FOR DATABASE
Dim conn2 As New ADODB.Connection ' CONNECTION FOR DATABASE
Dim excelFILE As String

Private Sub cmdBrowse_Click()
    Dim fileName As String
    fileName = getExcelFile()
    If fileName <> "" Then
        fileTxt.Text = fileName
        excelFILE = fileName
    End If
End Sub

Private Function getExcelFile()
On Error GoTo cancelled
   
    CommonDialog.CancelError = True
    CommonDialog.DialogTitle = "Select Excel File"
    CommonDialog.Filter = "Excel (*.xls) | *.xls"
    CommonDialog.Flags = cdlOFNFileMustExist ' SHOW IF FILE DOES NOT EXIST
    CommonDialog.ShowOpen
    getExcelFile = CommonDialog.fileName
   
cancelled:
End Function

Public Sub performExcelFetch()

Set conn1 = New ADODB.Connection
Set rs1 = New ADODB.Recordset
Set conn2 = New ADODB.Connection

 conn1.Open "Driver={Microsoft Excel Driver (*.xls)};Dbq=" & excelFILE & ""
 conn2.Open "DRIVER={MySQL ODBC 3.51 Driver};" _
            & "SERVER=127.0.0.1;" _
            & "DATABASE=skemo;" _
            & "UID=root;" _
            & "PWD=;"

      
 sql = "SELECT * FROM [Sheet1$]"
 rs1.Open sql, conn1
 
 acCtr = 1
 Do Until rs1.EOF
  
  auth_code = ""
  crs = ""
  req = ""
  unit = ""
  dep = ""
  sub_type = ""
  crs_title = ""
  curriculum = ""
  
  
  crs = UCase(Trim(rs1!course_no))
  req = UCase(rs1!requires)
  unit = Trim(rs1!units)
  dep = Trim(UCase(rs1!department))
  curriculum = Trim(rs1!curr_type)
  crs_title = Trim(rs1!course_desc)
   
   'If IsNull(req) Or req = "" Then
   ' req = "NONE"
   'End If
   
   req = Split(req, ",")
     
     'Desc = Replace(Desc, "'", "")  'replace space with underscore
    
     If crs_title = "" Then
      crs_title = "NOT AVAILABLE"
     End If
     
     sub_curr_year = Right(curriculum, 2)
     
     auth_code = crs & "_" & dep & sub_curr_year
     
     For x = 0 To UBound(req)
       sql = "INSERT INTO prospect_require VALUES ('" & auth_code & "','" & Trim(req(x)) & "')"
       Call checkDBError(sql)
       sql = ""
     Next
     
       sql = "INSERT INTO prospectus VALUES('" & auth_code & "','" & crs & "','" & curriculum & "','" _
        & crs_title & "','" & unit & "','" & dep & "')"
       
       Call checkDBError(sql)
       sql = ""
 'acCtr = acCtr + 1
 rs1.MoveNext
 Loop
 MsgBox "Finished"
End Sub
Private Sub checkDBError(sqlQuery)
 Dim err As Error
  On Error GoTo errHandler
  rs2.Open sqlQuery, conn2
  
errHandler:
 For Each err In conn2.Errors
 MsgBox "DB Error: " & err.Description
 ' DO NOTHING HERE
 ' PWEDE PD PROMPT FORMAL ERROR MESSAGE
 Next
 Resume Next
End Sub
Private Sub cmdExtract_Click()
 If fileTxt.Text <> "Select Excel File" Then
  Call performExcelFetch
 Else
  MsgBox "No File Found", vbOKOnly, "Error Opening File"
 End If
End Sub

