# language: ja
フィーチャ: 登録データの出力
  最新情報を確認するため
  ユーザとして
  新着記事のリストを取得する

  背景:
    前提 "iPhone5" で接続する

  @tableTestData
  シナリオ: 新着記事のリストが取得できること
    前提 以下の記事が登録されていること
      | title        | category   |
      | ライフの記事 | ライフ     |
      | 結婚の記事   | 結婚       |
      | 恋愛の記事   | 恋愛       |

    かつ "/category/520" へ移動する
    ならば レスポンスコードが 200 であること
    かつ "恋愛相談" と表示されていること
