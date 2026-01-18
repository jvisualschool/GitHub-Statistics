# GitHub Stat. — GitHub 3D Isometric Contributions Dashboard

**jvisualschool**의 GitHub 활동을 시각화하는 인터랙티브 대시보드입니다.  
GitHub의 기여도 데이터를 **3D Isometric Chart**, **Heatmap**, **실시간 통계**로 보여주며, 다크/라이트 모드 및 다양한 컬러 테마를 지원합니다.

> **운영 URL**: [https://jvibeschool.org/GHSTAT/](https://jvibeschool.org/GHSTAT/)

![Dashboard Overview](screenshot/screenshot1.png)

---

## 🚀 주요 기능 (Key Features)

1. **Interactive Splash Screen (Intro)**
   - 초기 로딩 시 **2초간** 우아한 스플래시 화면 노출 (`splash.jpg` + 배경 Blur 효과)
   - 기술 스택 및 저작권 정보 포함, 클릭 시 닫기/다시 열기 인터랙션 지원
   - 좌측 상단 브랜드 아이콘(고양이) 클릭 시 스플래시 화면 재호출 가능

2. **Featured Projects (Dynamic)**
   - GitHub GraphQL API를 통해 **Stargazers 수 기준 상위 4개 프로젝트** 자동 노출
   - 각 프로젝트 카드에 **3D Tilt Effect** (마우스 움직임에 반응) 적용
   - 프로젝트 설명, 태그, Star/Fork 수, 업데이트 날짜 실시간 표시

3. **3D Isometric Contribution Graph**
   - [Obelisk.js](https://github.com/nosir/obelisk.js)를 활용하여 GitHub 기여도를 입체적인 3D 큐브로 시각화
   - 14주간의 활동 타임라인을 입체적으로 표현
   - 마우스 호버 시 날짜별 기여 횟수 툴팁 제공

4. **실시간 통계 및 분석 (Real-time Stats)**
   - **Total Contributions**: 연간 총 기여 수 및 상세 활동(Commits, Issues, PRs) 집계
   - **Streaks**: 최장 연속 기여일 및 현재 연속 기여일 자동 계산
   - **User Details**: 가입일, 팔로워, 기여한 저장소 수, 디스크 사용량 등 상세 정보 표시

5. **다양한 시각화 차트**
   - **Language Mix**: 주 사용 언어 비율 분석 (Chart.js - Doughnut Chart)
   - **Contribution Heatmap**: 소스 코드 링크 연동 (타이틀 옆 아이콘 클릭 시 원본 소스 이동)

6. **테마 시스템 (Theme System)**
   - **Light / Dark Mode**: 시스템 설정 또는 유저 토글을 통한 모드 전환 (완벽한 디자인 대응)
   - **5 Color Themes**: Green, Blue, Purple, Orange, Red 컬러 테마 지원
   - 모든 차트, 아이콘, 텍스트가 선택된 테마 색상에 맞춰 동적으로 변경됨

7. **보안 아키텍처 (Security)**
   - 클라이언트(브라우저)에는 GitHub 토큰을 노출하지 않음
   - **PHP Proxy**를 통해 서버 사이드에서만 안전하게 GitHub GraphQL API 통신

---

## 📸 Gallery

| 3D Visualization | Real-time Stats |
| :---: | :---: |
| ![3D Chart](screenshot/screenshot2.png) | ![Stats](screenshot/screenshot3.png) |

---

## 🛠 기술 스택 (Tech Stack)

- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Libraries**:
  - [Chart.js](https://www.chartjs.org/) (2D Charts)
  - [Obelisk.js](https://github.com/nosir/obelisk.js) (3D Isometric Rendering)
  - [Font Awesome](https://fontawesome.com/) (Icons)
- **Backend**: PHP (API Proxy)
- **API**: [GitHub GraphQL API](https://docs.github.com/en/graphql)

---

## 📂 파일 구조 (File Structure)

```
GHSTAT/
├── index.html               # 메인 대시보드 페이지 (Frontend)
├── splash.jpg               # 인트로 스플래시 이미지 (480px width)
├── screenshot/              # 프로젝트 스크린샷 이미지
├── api/
│   └── github-graphql.php   # GitHub API 프록시 (Backend)
├── vendor/                  # 외부 라이브러리 (Obelisk.js 등)
└── README.md                # 프로젝트 문서
```

---

## 📦 설치 및 배포 가이드 (Deployment)

이 프로젝트는 **AWS Lightsail (Bitnami LAMP Stack)** 환경을 기준으로 구성되었습니다.

### 1. 웹 리소스 배포
`index.html` 및 `splash.jpg` 파일을 웹 서버의 `GHSTAT` 디렉토리에 업로드합니다.
- 경로 예시: `/home/bitnami/htdocs/GHSTAT/`

### 2. API 프록시 설정
`api/github-graphql.php` 파일을 생성하고 업로드합니다.
- 경로 예시: `/home/bitnami/htdocs/GHSTAT/api/github-graphql.php`

### 3. 보안 토큰 설정 (중요)
`github_token.txt` 파일을 **웹 루트 외부**에 생성하여 안전하게 관리합니다.
브라우저에서 직접 접근할 수 없는 위치여야 합니다.

```bash
# 1. 보안 디렉토리 생성
sudo mkdir -p /home/bitnami/secure

# 2. 토큰 파일 생성 (GitHub Personal Access Token 입력)
sudo vi /home/bitnami/secure/github_token.txt
# 예: ghp_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

# 3. 권한 설정 (소유자만 읽기 가능)
sudo chmod 600 /home/bitnami/secure/github_token.txt
sudo chown bitnami:bitnami /home/bitnami/secure/github_token.txt
```

### 4. 프록시 동작 확인
```bash
curl -sS -X POST "https://your-domain.com/GHSTAT/api/github-graphql.php" \
  -H "Content-Type: application/json" \
  -d '{"query":"query { viewer { login } }"}'
```

---

## 🔗 관련 링크
- **GitHub**: [https://github.com/jvisualschool/](https://github.com/jvisualschool/)
- **Website**: [https://www.jvisualschool.com/](https://www.jvisualschool.com/)
- **License**: MIT License

---
2026 (c) **Jinho Jung** (AI Vibe Coding Specialist)
