# language: ja
フィーチャ: ドコモカテゴリナビ向けAPI
  カテゴリナビ向けAPIが動作していることを確認する

  シナリオアウトライン: APIへのアクセスでエラーが発生していないことを確認する
    前提 "<path>" へ移動する
    ならば レスポンスコードが 200 であること

    例:
      | name                 | path                                  |
      | QAページ             | /qa/5564717.html                      |
      | 検索結果             | /search_goo/result?MT=wii&code=utf8   |
      | トゥデイ             | /today                                |
