/* Yazılan makaleler */

.article-content {
  padding: 30px 0;
  color: #e0e0e0; /* Yazı rengi iyileştirildi */
}

.container {
  max-width: 1100px;
  margin: 40px auto;
  background: #1c1c1c; /* Daha koyu bir arka plan */
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
  color: #f4f4f4; /* Yazı rengi biraz daha açık yapıldı */
}

.main-title {
  font-size: 3em;
  color: orangered;
  margin-bottom: 15px;
  text-align: center; /* Başlık ortalandı */
}

.sub-title {
  font-size: 2.2em;
  color: #80a1b9; /* Daha yumuşak bir mavi ton */
  margin-bottom: 25px;
  text-align: center; /* Alt başlık ortalandı */
}

.acheader-tittle {
  font-size: 1.8em;
  color: #7cb342; /* Yeşil tonunu biraz daha canlı hale getirdik */
  margin-bottom: 20px;
  margin-top: 50px;
  text-align: center; /* Başlık ortalandı */
}

.p-content {
  font-size: 1.1em;
  line-height: 1.8;
  color: #f4f4f4; /* Yazı rengi daha okunabilir yapıldı */
  margin-bottom: 50px;
}

.content {
  font-size: 1.1em;
  line-height: 1.8;
  color: #c8c8c8; /* Yazı rengi biraz daha açık */
  margin-bottom: 30px;
}

/* Mobile Responsive Ayarları */
@media (max-width: 768px) {
  .container {
    padding: 15px;
    max-width: 100%;
  }

  .main-title {
    font-size: 2.5em;
  }

  .sub-title, .acheader-tittle {
    font-size: 1.7em;
  }

  .p-content, .content {
    font-size: 1em;
  }
}

/* Yorumlar Bölümü */
.comments-section {
  background-color: #f4f6f9;
  color: #333;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin: 20px auto; /* Ortalamak ve üstte boşluk bırakmak için */
  max-width: 1300px; /* Maksimum genişliği arttırarak büyütme */
}

/* Yorum Formu */
.comments-section .comment-form-container {
  display: flex;
  flex-direction: column;
  margin-bottom: 20px;
}

.comments-section .comment-form-container textarea {
  width: 100%;
  padding: 15px;
  font-size: 16px;
  border: 1px solid #ddd;
  border-radius: 8px;
  resize: none;
  min-height: 100px;
  margin-bottom: 15px;
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.comments-section .comment-form-container button {
  padding: 10px 20px;  /* Biraz daha büyük boyutlar */
  background-color: #28a745;
  border: none;
  border-radius: 8px;
  color: white;
  font-size: 16px;  /* Font boyutunu biraz daha büyüttük */
  cursor: pointer;
  align-self: flex-end;
  transition: background-color 0.3s;
}

.comments-section .comment-form-container button:hover {
  background-color: #218838;
}

/* Bireysel Yorum */
.comments-section .comment {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  position: relative;
}

.comments-section .comment p {
  margin: 0 0 10px;
  font-size: 16px;
}

.comments-section .comment small {
  display: block;
  font-size: 14px;
  color: #999;
}

/* Yorumlara Yanıt */
.comments-section .reply {
  margin-top: 15px;
  padding-left: 20px;
  border-left: 3px solid #ddd;
}

/* Genişletme/Daraltma Düğmesi */
.comments-section .toggle-replies {
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 8px;
  padding: 8px 16px;  /* Biraz daha büyük boyutlar */
  font-size: 16px;  /* Font boyutunu biraz daha büyüttük */
  cursor: pointer;
  margin-top: 10px;
  transition: background-color 0.3s;
}

.comments-section .toggle-replies:hover {
  background-color: #0056b3;
}
