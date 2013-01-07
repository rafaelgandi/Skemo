VERSION 5.00
Begin VB.Form confirmPass 
   BackColor       =   &H00E0E0E0&
   Caption         =   "Skemo™ Utility"
   ClientHeight    =   3240
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   4455
   Icon            =   "confirmPass.frx":0000
   LinkTopic       =   "Form1"
   ScaleHeight     =   3240
   ScaleWidth      =   4455
   StartUpPosition =   1  'CenterOwner
   Begin VB.CommandButton cmdPass 
      Caption         =   "&OK"
      Height          =   375
      Left            =   2760
      TabIndex        =   1
      Top             =   1200
      Width           =   735
   End
   Begin VB.TextBox txtPass 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
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
      IMEMode         =   3  'DISABLE
      Left            =   1200
      PasswordChar    =   "*"
      TabIndex        =   2
      Top             =   1200
      Width           =   1455
   End
   Begin VB.Label Label2 
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
      Left            =   3240
      TabIndex        =   4
      Top             =   2520
      Width           =   615
   End
   Begin VB.Label Label1 
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
      Left            =   2160
      TabIndex        =   3
      Top             =   2520
      Width           =   2535
   End
   Begin VB.Shape Shape1 
      FillColor       =   &H000040C0&
      FillStyle       =   0  'Solid
      Height          =   375
      Left            =   0
      Top             =   2880
      Width           =   4695
   End
   Begin VB.Label labelPass 
      BackColor       =   &H00E0E0E0&
      Caption         =   "Enter Utiity Password"
      BeginProperty Font 
         Name            =   "Tahoma"
         Size            =   12
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   1200
      TabIndex        =   0
      Top             =   480
      Width           =   2295
   End
End
Attribute VB_Name = "confirmPass"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False

Private Sub cmdPass_Click()
 Call passChecker(txtPass.Text)
End Sub

Private Sub Form_Load()
 txtPass.Text = ""
End Sub

Private Sub Form_Unload(Cancel As Integer)
 confirmPass.Hide
 Unload Me
End Sub

Private Sub txtPass_KeyPress(KeyAscii As Integer)
 If KeyAscii = 13 Then ' User Press Enter
  passChecker (txtPass.Text)
 End If
End Sub

Private Sub passChecker(inputPass As String)

If txtPass.Text = "omeks" Then
 'Call initializer("END_SYSTEM")
 Call Shell_NotifyIcon(NIM_DELETE, iconData)
 Unload skemoForm
 Unload Me
Else
 confirmPass.Hide
End If

End Sub


